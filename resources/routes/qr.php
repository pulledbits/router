<?php return function(\Aura\Router\Map $map) {
    $map->get('qr', '/qr', function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Request $request) : void {
        $query = $request->getQueryParams();
        if (array_key_exists('data', $query) === false) {
            $request->respond(400, 'Query incomplete');
        } elseif ($query['data'] === null) {
            $request->respond(400, 'Query data incomplete');
        } else  {
            $request->respondWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview('qr')->capture([
                'data' => $query['data'],
                'qr' => function (int $width, int $height, string $data) use ($resources) : void {
                    $renderer = $resources->qrRenderer($width, $height);
                    $writer = $resources->qrWriter($renderer);
                    print $writer->writeString($data);
                }
            ]));
        }
    });
};