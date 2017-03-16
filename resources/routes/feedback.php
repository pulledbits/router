<?php return function(\Aura\Router\Map $map) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Request $request) : void {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);
        $request->respond(200, $resources->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};