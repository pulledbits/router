<?php return function(\Aura\Router\Map $map) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $resources, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        return $resources->response(200, $resources->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};