<?php return function(\rikmeijer\Teach\Resources $bootstrap, \Aura\Router\Map $map) {
    $map->get('contactmoment.prepare-import', '/contactmoment/import', function (array $attributes, array $query) use ($bootstrap) : \Psr\Http\Message\ResponseInterface {
        $session = $bootstrap->session();
        return $bootstrap->response(200, $bootstrap->phpview('contactmoment/import')->capture([
            'importForm' => function() use ($session) : void {
                $model = 'ICS URL: <input type="text" name="url" />';

                $this->form("post", $session->getCsrfToken()->getValue(), "Importeren", $model);
            }
        ]));
    });

    $map->post('contactmoment.import', '/contactmoment/import', function (array $attributes, array $query, array $payload) use ($bootstrap) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();

        $icalReader = $bootstrap->iCalReader($payload['url']);

        foreach ($icalReader->events() as $event) {
            if (array_key_exists('SUMMARY', $event) === false) {
                continue;
            }

            if (preg_match('/(?<module>[A-Z]+\d{1,2})/', $event['SUMMARY'], $matches) !== 1) {
                $module = $schema->initializeRecord('module', ['naam' => null]);
            } else {
                $module = $schema->readFirst('module', [], ['naam' => $matches['module']]);
            }

            if ($module->id === null) {
                continue;
            }

            $starttijd = new \DateTime($event['DTSTART']);
            $eindtijd = new \DateTime($event['DTEND']);

            $starttijd->setTimezone(new \DateTimeZone(ini_get('date.timezone')));
            $eindtijd->setTimezone(new \DateTimeZone(ini_get('date.timezone')));

            $contactmoment = $schema->readFirst('contactmoment', [], ['ical_uid' => $event['UID']]);

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

            $contactmoment->ical_uid = $event['UID'];
            $contactmoment->starttijd = $starttijd->format('Y-m-d H:i:s');
            $contactmoment->eindtijd = $eindtijd->format('Y-m-d H:i:s');
            $contactmoment->ruimte = $event['LOCATION'];
            $contactmoment->updated_at = date('Y-m-d H:i:s');
        }

        // remove future, imported contactmomenten which where not touched in this batch (today)
        $schema->delete('contactmoment_toekomst_geimporteerd_verleden', []);

        return $bootstrap->response(201, $bootstrap->phpview('contactmoment/imported')->capture([]));
    });
};