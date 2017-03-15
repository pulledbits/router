<?php return function(\Aura\Router\Map $map) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $bootstrap, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        return $bootstrap->response(200, $bootstrap->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};