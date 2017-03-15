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
};