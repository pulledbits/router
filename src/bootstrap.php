<?php
function extractModule(\pulledbits\ActiveRecord\Schema $schema, string $summary)
{
    if (preg_match('/(?<module>[A-Z]+\d{1,2})/', $summary, $matches) !== 1) {
        return $schema->initializeRecord('module', ['naam' => null]);
    }
    return $schema->readFirst('module', [], ['naam' => $matches['module']]);
}

function importEvent(\pulledbits\ActiveRecord\Schema $schema, \pulledbits\ActiveRecord\Record $module, \DateTime $starttijd, \DateTime $eindtijd, string $uid, string $ruimte)
{
    $starttijd->setTimezone(new \DateTimeZone(ini_get('date.timezone')));
    $eindtijd->setTimezone(new \DateTimeZone(ini_get('date.timezone')));

    $contactmoment = $schema->readFirst('contactmoment', [], ['ical_uid' => $uid]);

    if ($contactmoment->les_id === null) {
        $lesplan = $module->fetchFirstByFkLesmodule([
            'jaar' => $starttijd->format('Y'),
            'kalenderweek' => ltrim($starttijd->format('W'), '0')
        ]);

        if ($lesplan->naam === null) {
            $lesplan->naam = "";
        }
        $contactmoment->les_id = $lesplan->id;
    }

    $contactmoment->ical_uid = $uid;
    $contactmoment->starttijd = $starttijd->format('Y-m-d H:i:s');
    $contactmoment->eindtijd = $eindtijd->format('Y-m-d H:i:s');
    $contactmoment->ruimte = $ruimte;
    $contactmoment->updated_at = date('Y-m-d H:i:s');
}

return function() : \Aura\Router\Matcher {
    /**
     * @var $bootstrap \pulledbits\ActiveRecord\Resources
     */
    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $routerContainer = $bootstrap->router();
    $map = $routerContainer->getMap();

    $map->get('index', '/', function (array $attributes, array $query) use ($bootstrap) {
        $schema = $bootstrap->schema();

        $protocol = 'http';
        if (array_key_exists('HTTPS', $_SERVER)) {
            $protocol .= 's';
        }

        return $bootstrap->phpview('welcome')->render([
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], []),
            'hostname' => $protocol . '://' . $_SERVER['HTTP_HOST']
        ]);
    });

    $map->get('contactmoment.prepare-import', '/contactmoment/import', function (array $attributes, array $query) use ($bootstrap) {
        $session = $bootstrap->session();

        return $bootstrap->phpview('contactmoment/import')->render([
            'csrf_value' => $session->getCsrfToken()->getValue()
        ]);
    });
    $map->post('contactmoment.import', '/contactmoment/import', function (array $attributes, array $query, array $payload) use ($bootstrap) {
        $schema = $bootstrap->schema();

        switch ($payload["type"]) {
            case "ics":
                $url = $payload['url'];
                $icalReader = new \ICal($url);

                foreach ($icalReader->events() as $event) {
                    if (array_key_exists('SUMMARY', $event) === false) {
                        continue;
                    }

                    $module = extractModule($schema, $event['SUMMARY']);
                    if ($module->id === null) {
                        continue;
                    }

                    if (preg_match('/Groepen:\s+(?<groepen>[^\\n]+)\\\\n\\\\n/', $event['DESCRIPTION'], $groepMatches) === 1) {
                        $groepen = explode('\, ', $groepMatches['groepen']);
                    }

                    importEvent($schema, $module, new \DateTime($event['DTSTART']), new \DateTime($event['DTEND']), $event['UID'], $event['LOCATION']);
                }

                // remove future, imported contactmomenten which where not touched in this batch (today)
                $schema->delete('contactmoment_toekomst_geimporteerd_verleden', []);
                break;

            case 'avansroosterjson':
                foreach (json_decode($payload['json'], true) as $event) {
                    $module = extractModule($schema, $event['vak']);
                    if ($module->id === null) {
                        continue;
                    } elseif (preg_match('/in\s+\<a[^\>]+\><span[^\>]+\>(?<ruimte>\w+)/', $event['title'], $matches) !== 1) {
                        continue;
                    }

                    $ruimte = $matches['ruimte'];
                    $uid = 'Ical' . $event['start'] . $event['end'] . $ruimte . $event['vak'] . $event['param'] . '@rooster.avans.nl';
                    $uid = str_replace('-', '', $uid);
                    $uid = str_replace(' ', '', $uid);
                    importEvent($schema, $module, new \DateTime($event['start']), new \DateTime($event['end']), $uid, $ruimte);
                }
                break;

            default:
                return abort(500, 'Unsupported import type');
        }
        return $bootstrap->phpview('contactmoment/imported')->render([]);
    });

    $map->get('feedback.view', '/feedback/{contactmomentIdentifier}', function (array $attributes, array $query) use ($bootstrap) {
        $schema = $bootstrap->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        return $bootstrap->phpview('feedback')->render([
            'contactmoment' => $contactmoment
        ]);
    });
    $map->get('feedback.prepare-supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query) use ($bootstrap) {
        $schema = $bootstrap->schema();
        $session = $bootstrap->session();

        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);

        $ipRating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);

        if ($ipRating !== null) {
            $data = [
                'rating' => $ipRating->waarde,
                'explanation' => $ipRating->inhoud
            ];
        } else {
            $data = null;
        }

        if ($data !== null) {
            $rating = $data['rating'];
            $explanation = $data['explanation'];
        } else {
            $rating = null;
            $explanation = null;
        }

        if (array_key_exists('rating', $query)) {
            $rating = $query['rating'];
        }

        $starData = $bootstrap->readAssetStar();
        $unstarData = $bootstrap->readAssetUnstar();
        return $bootstrap->phpview('feedback/supply')->render([
            'rating' => $rating,
            'explanation' => $explanation,
            'csrf_value' => $session->getCsrfToken()->getValue(),
            'uris' => [
                'star' => 'data:image/png;base64,' . base64_encode($starData),
                'unstar' => 'data:image/png;base64,' . base64_encode($unstarData)
            ]
        ]);
    });
    $map->post('feedback.supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query, array $payload) use ($bootstrap) {
        $schema = $bootstrap->schema();
        $session = $bootstrap->session();

        $csrf_token = $session->getCsrfToken();
        if ($csrf_token->isValid($payload['__csrf_value']) === false) {
            return "This looks like a cross-site request forgery.";
        }
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        $rating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);
        $rating->waarde = $payload['rating'];
        $rating->inhoud = $payload['explanation'];
        if ($rating->created_at === null) {
            $rating->created_at = date('Y-m-d H:i:s');
        }
        $rating->updated_at = date('Y-m-d H:i:s');
        return 'Dankje!';
    });

    $map->get('rating.view', '/rating/{contactmomentIdentifier}', function (array $attributes, array $query) use ($bootstrap) {
        $schema = $bootstrap->schema();

        return $bootstrap->phpview('rating')->render([
            'rating' => $schema->readFirst('contactmomentrating', [], ['contactmoment_id' => $attributes['contactmomentIdentifier']])->waarde,
            'starData' => $bootstrap->readAssetStar(),
            'unstarData' => $bootstrap->readAssetUnstar()
        ]);
    });
    $map->get('qr.view', '/qr', function (array $attributes, array $query) use ($bootstrap) {
        $data = $query['data'];
        if ($data === null) {
            return abort(400);
        }

        $template = $bootstrap->phpview('qr');
        $template->render([
            'qr' => function(int $width, int $height) use ($bootstrap, $data) : string {
                $renderer = $bootstrap->qrRenderer($width, $height);
                $writer = $bootstrap->qrWriter($renderer);
                return $writer->writeString($data);
            }
        ]);
    });

    return $routerContainer->getMatcher();
};