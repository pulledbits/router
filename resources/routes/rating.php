<?php return function(\Aura\Router\Map $map) {
    $map->get('rating', '/rating/{contactmomentIdentifier}', function (\rikmeijer\Teach\Resources $bootstrap, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();

        return $bootstrap->response(200, $bootstrap->phpview('rating')->capture([
            'rating' => $schema->readFirst('contactmomentrating', [], ['contactmoment_id' => $attributes['contactmomentIdentifier']])->waarde,
            'starData' => $bootstrap->readAssetStar(),
            'unstarData' => $bootstrap->readAssetUnstar()
        ]))->withAddedHeader('Content-Type', 'image/png');
    });
};