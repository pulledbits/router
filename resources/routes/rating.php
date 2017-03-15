<?php return function(\Aura\Router\Map $map) {
    $map->get('rating', '/rating/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $resources, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();

        return $resources->response(200, $resources->phpview('rating')->capture([
            'rating' => $schema->readFirst('contactmomentrating', [], ['contactmoment_id' => $attributes['contactmomentIdentifier']])->waarde,
            'starData' => $resources->readAssetStar(),
            'unstarData' => $resources->readAssetUnstar()
        ]))->withAddedHeader('Content-Type', 'image/png');
    });
};