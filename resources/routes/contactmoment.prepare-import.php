<?php return function(\Aura\Router\Map $map, \rikmeijer\Teach\Resources $resources) {
    $map->get('contactmoment.prepare-import', '/contactmoment/import', function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $session = $resources->session();
        return $response->send(200, $resources->phpview('contactmoment/import')->capture([
            'importForm' => function() use ($session) : void {
                $model = 'ICS URL: <input type="text" name="url" />';

                $this->form("post", $session->getCsrfToken()->getValue(), "Importeren", $model);
            }
        ]));
    });
};