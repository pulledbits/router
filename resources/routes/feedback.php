<?php return function(\rikmeijer\Teach\Resources $bootstrap, \Aura\Router\Map $map) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (array $attributes, array $query) use ($bootstrap) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        return $bootstrap->response(200, $bootstrap->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};