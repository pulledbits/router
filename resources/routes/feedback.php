<?php return function(\Aura\Router\Map $map, \rikmeijer\Teach\Resources $resources) {
    $map->get('feedback', '/feedback/{contactmomentIdentifier}', function (\Psr\Http\Message\RequestInterface $request) use ($resources) : void {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);
        $this->send(200, $resources->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    });
};