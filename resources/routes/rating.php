<?php return function(\Aura\Router\Map $map) {
    $map->get('rating', '/rating/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Request $request) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();

        $request->respondWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview('rating')->capture([
            'rating' => $schema->readFirst('contactmomentrating', [], ['contactmoment_id' => $request->getAttribute('contactmomentIdentifier')])->waarde,
            'starData' => $resources->readAssetStar(),
            'unstarData' => $resources->readAssetUnstar()
        ]));
    });
};