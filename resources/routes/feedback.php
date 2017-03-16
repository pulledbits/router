<?php return function(\Aura\Router\Map $map) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response, array $attributes, array $query) : void {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        $response->send(200, $resources->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};