<?php
function makeBlade() : \duncan3dc\Laravel\BladeInstance
{
    static $blade;
    if ($blade === null) {
        $blade = new \duncan3dc\Laravel\BladeInstance(__DIR__ . "/resources/views", dirname(__DIR__) . "/storage/views.reboot");
    }
    return $blade;
}

function extractModule(\ActiveRecord\Schema $schema, string $summary)
{
    if (preg_match('/(?<module>[A-Z]+\d{1,2})/', $summary, $matches) !== 1) {
        return $schema->initializeRecord('module', ['naam' => null]);
    }
    return $schema->readFirst('module', [], ['naam' => $matches['module']]);
}

function importEvent(\ActiveRecord\Schema $schema, \ActiveRecord\Record $module, \DateTime $starttijd, \DateTime $eindtijd, string $uid, string $ruimte, array $groepcodes)
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

    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $schema = $bootstrap->schema();

    $session_factory = new \Aura\Session\SessionFactory;
    $session = $session_factory->newInstance($_COOKIE);

    $routerContainer = new \Aura\Router\RouterContainer();
    $map = $routerContainer->getMap();

    /*
     * |--------------------------------------------------------------------------
     * | Application Routes
     * |--------------------------------------------------------------------------
     * |
     * | Here is where you can register all of the routes for an application.
     * | It's a breeze. Simply tell Laravel the URIs it should respond to
     * | and give it the controller to call when that URI is requested.
     * |
     */
    $map->get('index', '/', function (array $attributes, array $query) use ($schema) {
        $ipv4Adresses = [
            $_SERVER['HTTP_HOST']
        ];
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec("ipconfig", $ipconfigData, $exitCode);
            if ($exitCode !== 0) {
                exit('failed retrieving ip adresses');
            }
            foreach ($ipconfigData as $line) {
                if (preg_match('/IPv4 Address(\.\s)+:\s(?<ipv4>\d+\.\d+\.\d+\.\d+)/', $line, $matches) === 1) {
                    $ipv4Adresses[] = $matches['ipv4'];
                }
            }
        } else {
            exec("ifconfig", $ipconfigData, $exitCode);
            if ($exitCode !== 0) {
                exit('failed retrieving ip adresses');
            }
            foreach ($ipconfigData as $line) {
                if (preg_match('/inet addr:(?<ipv4>\d+\.\d+\.\d+\.\d+)/', $line, $matches) === 1) {
                    $ipv4Adresses[] = $matches['ipv4'];
                }
            }
        }

        return makeBlade()->render('welcome', [
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], []),
            'ipv4Adresses' => $ipv4Adresses
        ]);
    });

    $map->get('contactmoment.prepare-import', '/contactmoment/import', function (array $attributes, array $query) use ($schema, $session) {
        return makeBlade()->render('contactmoment.import', [
            'csrf_value' => $session->getCsrfToken()->getValue()
        ]);
    });
    $map->post('contactmoment.import', '/contactmoment/import', function (array $attributes, array $query, array $payload) use ($schema) {
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

                    importEvent($schema, $module, new \DateTime($event['DTSTART']), new \DateTime($event['DTEND']), $event['UID'], $event['LOCATION'], $groepen);
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
                    importEvent($schema, $module, new \DateTime($event['start']), new \DateTime($event['end']), $uid, $ruimte, []);
                }
                break;

            default:
                return abort(500, 'Unsupported import type');
        }
        return makeBlade()->render("contactmoment.imported", []);
    });

    $map->get('feedback.view', '/feedback/{contactmomentIdentifier}', function (array $attributes, array $query) use ($schema) {
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);

        if (array_key_exists('HTTPS', $_SERVER) === false) {
            $scheme = 'http';
        } else {
            $scheme = 'https';
        }

        return makeBlade()->render('feedback', [
            'contactmoment' => $contactmoment,
            'url' => $scheme . '://' . $_SERVER['HTTP_HOST'] . '/feedback/' . $contactmoment->id . '/supply'
        ]);
    });
    $map->get('feedback.prepare-supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query) use (     $session, $schema) {
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);

        $assetsDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets';

        $ipRating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);

        $imageStar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'star.png';
        $starData = base64_encode(file_get_contents($imageStar));

        $imageUnstar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'unstar.png';
        $unstarData = base64_encode(file_get_contents($imageUnstar));

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

        return makeBlade()->render('feedback/supply', [
            'rating' => $rating,
            'explanation' => $explanation,
            'csrf_value' => $session->getCsrfToken()->getValue(),
            'uris' => [
                'star' => 'data: ' . mime_content_type($imageStar) . ';base64,' . $starData,
                'unstar' => 'data: ' . mime_content_type($imageUnstar) . ';base64,' . $unstarData
            ]
        ]);
    });
    $map->post('feedback.supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query, array $payload) use ($session, $schema) {
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
        return 'Dankje!';
    });

    $map->get('rating.view', '/rating/{contactmomentIdentifier}', function (array $attributes, array $query) use ($schema) {
        $assetsDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets';
        $imageStar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'star.png';
        $imageUnstar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'unstar.png';

        return makeBlade()->render('rating', [
            'rating' => $schema->readFirst('contactmomentrating', [], ['contactmoment_id' => $attributes['contactmomentIdentifier']])->waarde,
            'starData' => file_get_contents($imageStar),
            'unstarData' => file_get_contents($imageUnstar)
        ]);
    });
    $map->get('qr.view', '/qr', function (array $attributes, array $query) use ($schema) {
        $data = $query['data'];
        if ($data === null) {
            return abort(400);
        }
        return makeBlade()->render('qr', [
            'data' => $data
        ]);
    });

    return $routerContainer->getMatcher();
};