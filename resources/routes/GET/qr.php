<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $query = $request->getQueryParams();
        if (array_key_exists('data', $query) === false) {
            return $response->send(400, 'Query incomplete');
        } elseif ($query['data'] === null) {
            return $response->send(400, 'Query data incomplete');
        } else  {
            return $response->sendWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview('qr')->capture([
                'data' => $query['data'],
                'qr' => function (int $width, int $height, string $data) use ($resources) : void {
                    $renderer = $resources->qrRenderer($width, $height);
                    $writer = $resources->qrWriter($renderer);
                    print $writer->writeString($data);
                }
            ]));
        }
};