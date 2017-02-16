<?php
function makeBlade() : \duncan3dc\Laravel\BladeInstance
{
    static $blade;
    if ($blade === null) {
        $blade = new \duncan3dc\Laravel\BladeInstance(__DIR__ . "/resources/views", dirname(__DIR__) . "/storage/views.reboot");
    }
    return $blade;
}

function extractModule(string $summary)
{
    if (preg_match('/(?<module>[A-Z]+\d{1,2})/', $summary, $matches) !== 1) {
        return null;
    }
    return \App\Module::where('naam', $matches['module'])->first();
}

function importEvent(\App\Module $module, \DateTime $starttijd, \DateTime $eindtijd, string $uid, string $ruimte, array $groepcodes)
{
    $starttijd->setTimezone(new \DateTimeZone(ini_get('date.timezone')));
    $eindtijd->setTimezone(new \DateTimeZone(ini_get('date.timezone')));

    $contactmoment = \App\Contactmoment::where('ical_uid', $uid)->first();
    if ($contactmoment === null) {
        $contactmoment = \App\Contactmoment::firstOrNew([
            'starttijd' => $starttijd
        ]);
    }

    $contactmoment->ical_uid = $uid;
    $contactmoment->starttijd = $starttijd;
    $contactmoment->eindtijd = $eindtijd;
    $contactmoment->ruimte = $ruimte;

    if ($contactmoment->les === null) {
        // try to find lesplan
        $lesplan = $module->lessen()->firstOrNew([
            'jaar' => $contactmoment->starttijd->format('Y'),
            'kalenderweek' => (int) $contactmoment->starttijd->format('W')
        ]);
        if ($lesplan->naam === null) {
            $lesplan->naam = "";
        }

        $lesplan->doelgroep()->associate(\App\Doelgroep::find(1));
        $lesplan->save();

        $contactmoment->les()->associate($lesplan);
    }

    foreach ($groepcodes as $groepcode) {
        $blokgroep = \App\Blokgroep::firstOrNew([
            'code' => $groepcode
        ]);
        $blokgroep->collegejaar = '1516';
        if (preg_match('/42IN(?<bloknummer>\d+)SO\w/', $groepcode, $matches) !== 1) {
            continue;
        }
        $blokgroep->nummer = (int) $matches['bloknummer'];
        $blokgroep->save();
    }

    $contactmoment->save();
}

return function() : \Aura\Router\Matcher {

    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $schema = $bootstrap(__DIR__ . DIRECTORY_SEPARATOR . 'gen' . DIRECTORY_SEPARATOR . 'factory.php');

    $routerContainer = new \Aura\Router\RouterContainer();
    $map = $routerContainer->getMap();

    makeBlade()->directive('formbuilder', function($expression) {
        return '<?php $formbuilder = new \AdamWathan\Form\FormBuilder' . $expression . '; ?>';
    });

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

    $map->get('contactmoment.prepare-import', '/contactmoment/import', function (array $attributes, array $query) use ($schema) {
        return makeBlade()->render('contactmoment.import', []);
    });
    $map->post('contactmoment.import', '/contactmoment/import', function (array $attributes, array $query, array $payload) use ($schema) {
        switch (\Request::get("type")) {
            case "ics":
                $url = \Request::get("url");
                $icalReader = new \ICal($url);

                foreach ($icalReader->events() as $event) {
                    if (array_key_exists('SUMMARY', $event) === false) {
                        continue;
                    }

                    $module = extractModule($event['SUMMARY']);
                    if ($module === null) {
                        continue;
                    }

                    if (preg_match('/Groepen:\s+(?<groepen>[^\\n]+)\\\\n\\\\n/', $event['DESCRIPTION'], $groepMatches) === 1) {
                        $groepen = explode('\, ', $groepMatches['groepen']);
                    }

                    importEvent($module, new \DateTime($event['DTSTART']), new \DateTime($event['DTEND']), $event['UID'], $event['LOCATION'], $groepen);
                }

                // remove future, imported contactmomenten which where not touched in this batch (today)
                \App\Contactmoment::where(function ($query) {
                    $query->where('updated_at', '<', date('Y-m-d'))->orWhereNull('updated_at');
                })->where('starttijd', '>', date('Y-m-d'))
                    ->whereNotNull('ical_uid')
                    ->delete();
                break;

            case 'avansroosterjson':
                foreach (json_decode(\Request::get("json"), true) as $event) {
                    $module = extractModule($event['vak']);
                    if ($module === null) {
                        continue;
                    } elseif (preg_match('/in\s+\<a[^\>]+\><span[^\>]+\>(?<ruimte>\w+)/', $event['title'], $matches) !== 1) {
                        continue;
                    }

                    $ruimte = $matches['ruimte'];
                    $uid = 'Ical' . $event['start'] . $event['end'] . $ruimte . $event['vak'] . $event['param'] . '@rooster.avans.nl';
                    $uid = str_replace('-', '', $uid);
                    $uid = str_replace(' ', '', $uid);
                    importEvent($module, new \DateTime($event['start']), new \DateTime($event['end']), $uid, $ruimte, []);
                }
                break;

            default:
                return abort(500, 'Unsupported import type');
        }
        return view("contactmoment.imported");
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
    $map->get('feedback.prepare-supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query) use ($schema) {
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
            'uris' => [
                'star' => 'data: ' . mime_content_type($imageStar) . ';base64,' . $starData,
                'unstar' => 'data: ' . mime_content_type($imageUnstar) . ';base64,' . $unstarData
            ]
        ]);
    });
    $map->post('feedback.supply', '/feedback/{contactmoment}/supply', function (array $attributes, array $query, array $payload) use ($schema) {
        $rating = $contactmoment->ratings()->firstOrNew([
            'ipv4' => $_SERVER['REMOTE_ADDR'],
            'waarde' => $request->rating
        ]);
        $rating->inhoud = $request->explanation;
        $rating->save();
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