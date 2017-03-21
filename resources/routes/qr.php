<?php return function(\Aura\Router\Map $map, \rikmeijer\Teach\Resources $resources) {
    $map->get('qr', '/qr', function (\Psr\Http\Message\RequestInterface $request) use ($resources) : void {
        $query = $request->getQueryParams();
        if (array_key_exists('data', $query) === false) {
            $this->send(400, 'Query incomplete');
        } elseif ($query['data'] === null) {
            $this->send(400, 'Query data incomplete');
        } else  {
            $this->sendWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview('qr')->capture([
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