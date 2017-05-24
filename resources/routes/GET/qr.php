<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
        $query = $request->getQueryParams();
        if (array_key_exists('data', $query) === false) {
            return $response->make(400, 'Query incomplete');
        } elseif ($query['data'] === null) {
            return $response->make(400, 'Query data incomplete');
        } else  {
            return $response->makeWithHeaders(200, ['Content-Type' => 'image/png'], $resources->phpview()->capture('qr', [
                'data' => $query['data']
            ]));
        }
};