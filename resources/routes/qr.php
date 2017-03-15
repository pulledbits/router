<?php return function(\Aura\Router\Map $map) {
    $map->get('qr', '/qr', function (\rikmeijer\Teach\Resources $bootstrap, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $data = $query['data'];
        if ($data === null) {
            return $bootstrap->response(400);
        }

        return $bootstrap->response(200, $bootstrap->phpview('qr')->capture([
            'data' => $data,
            'qr' => function (int $width, int $height, string $data) use ($bootstrap) : void {
                $renderer = $bootstrap->qrRenderer($width, $height);
                $writer = $bootstrap->qrWriter($renderer);
                print $writer->writeString($data);
            }
        ]))->withAddedHeader('Content-Type', 'image/png');
    });
};