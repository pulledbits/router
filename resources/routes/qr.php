<?php return function(\Aura\Router\Map $map) {
    $map->get('qr', '/qr', function (\rikmeijer\Teach\Resources $resources, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $data = $query['data'];
        if ($data === null) {
            return $resources->phpview('error')->response(400);
        }

        return $resources->phpview('qr')->response(200, [
            'data' => $data,
            'qr' => function (int $width, int $height, string $data) use ($resources) : void {
                $renderer = $resources->qrRenderer($width, $height);
                $writer = $resources->qrWriter($renderer);
                print $writer->writeString($data);
            }
        ])->withAddedHeader('Content-Type', 'image/png');
    });
};