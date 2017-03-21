<?php return function(\Aura\Router\Map $map, \rikmeijer\Teach\Resources $resources) {
    $map->get('qr', '/qr', function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : void {
        $query = $request->getQueryParams();
        if (array_key_exists('data', $query) === false) {
            $response->send(400, 'Query incomplete');
        } elseif ($query['data'] === null) {
            $response->send(400, 'Query data incomplete');
        } else  {
            $response->sendWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview('qr')->capture([
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