<?php return function(\Aura\Router\Map $map, \rikmeijer\Teach\Resources $resources) {
    $map->get('rating', '/rating/{contactmomentIdentifier}', function (\Psr\Http\Message\RequestInterface $request) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();

        $this->sendWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview('rating')->capture([
            'rating' => $schema->readFirst('contactmomentrating', [], ['contactmoment_id' => $request->getAttribute('contactmomentIdentifier')])->waarde,
            'starData' => $resources->readAssetStar(),
            'unstarData' => $resources->readAssetUnstar()
        ]));
    });
};