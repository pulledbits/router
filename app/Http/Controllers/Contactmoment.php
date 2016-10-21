<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class Contactmoment extends Controller
{

    public function read(\App\Contactmoment $contactmoment)
    {
        // $code = request('code');
        // $googleService = \OAuth::consumer('Google');
        // if ($code === null) {
        // return redirect((string) $googleService->getAuthorizationUri() . "&hd=avans.nl");
        // }
        // $token = $googleService->requestAccessToken($code);
        // $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);
        $result = \Request::old('result');
        if ($result !== null) {
            switch ($result[0]) {
                case 'activiteit.created':
                    $path = explode('.', $result[1]['referencing_property']);
                    if (substr_compare($path[0], 'thema', 0) === 0) {
                        list ($themaQualifier, $referencedIndex) = explode('#', $path[0]);
                        foreach ($contactmoment->les->themas as $index => $thema) {
                            if ($index === (int) $referencedIndex) {
                                $thema->{$path[1]} = $result[1]['value'];
                                $thema->save();
                            }
                        }
                    } elseif ($path[0] === 'les') { // expect les
                        $contactmoment->les->{$path[1]} = $result[1]['value'];
                        $contactmoment->les->save();
                    } else {
                        return abort(400);
                    }
                    break;
                
                case 'activiteit.updated':
                    break;
                
                default:
                    return abort(500, 'Unknown result ' . $result[0]);
            }
        }
        
        return view('lesplan', [
            'contactmoment' => $contactmoment
        ]);
    }

    public function importFromURL()
    {
        switch (\Request::get("type")) {
            case "ics":
                $url = \Request::get("url");
                $icalReader = new \ICal($url);
                
                foreach ($icalReader->events() as $event) {
                    if (array_key_exists('SUMMARY', $event) === false) {
                        continue;
                    }
                    
                    $module = $this->extractModule($event['SUMMARY']);
                    if ($module === null) {
                        continue;
                    }

                    if (preg_match('/Groepen:\s+(?<groepen>[^\\n]+)\\\\n\\\\n/', $event['DESCRIPTION'], $groepMatches) === 1) {
                        $groepen = explode('\, ', $groepMatches['groepen']);
                    }

                    $this->importEvent($module, new \DateTime($event['DTSTART']), new \DateTime($event['DTEND']), $event['UID'], $event['LOCATION'], $groepen);
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
                    $module = $this->extractModule($event['vak']);
                    if ($module === null) {
                        continue;
                    } elseif (preg_match('/in\s+\<a[^\>]+\><span[^\>]+\>(?<ruimte>\w+)/', $event['title'], $matches) !== 1) {
                        continue;
                    }
                    
                    $ruimte = $matches['ruimte'];
                    $uid = 'Ical' . $event['start'] . $event['end'] . $ruimte . $event['vak'] . $event['param'] . '@rooster.avans.nl';
                    $uid = str_replace('-', '', $uid);
                    $uid = str_replace(' ', '', $uid);
                    $this->importEvent($module, new \DateTime($event['start']), new \DateTime($event['end']), $uid, $ruimte, []);
                }
                break;
            
            default:
                return abort(500, 'Unsupported import type');
        }
        return view("contactmoment.imported");
    }

    private function extractModule(string $summary)
    {
        if (preg_match('/(?<module>[A-Z]+\d{1,2})/', $summary, $matches) !== 1) {
            return null;
        }
        return \App\Module::where('naam', $matches['module'])->first();
    }

    private function importEvent(\App\Module $module, \DateTime $starttijd, \DateTime $eindtijd, string $uid, string $ruimte, array $groepcodes)
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
            $blokgroep = \App\Blokgroep::firstOrNew(['code' => $groepcode]);
            $blokgroep->collegejaar = '1516';
            if (preg_match('/42IN(?<bloknummer>\d+)SO\w/', $groepcode, $matches) !== 1) {
                continue;
            }
            $blokgroep->nummer = (int)$matches['bloknummer'];
            $blokgroep->save();
        }
        
        $contactmoment->save();
    }
}
