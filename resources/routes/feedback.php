<?php return function(\Aura\Router\Map $map) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $resources, \Psr\Http\Message\RequestInterface $request) : void {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);
        $this->respond(200, $resources->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};