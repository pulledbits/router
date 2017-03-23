<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);
        return $response->send(200, $resources->phpview('feedback')->capture([
            'contactmoment' => $contactmoment
        ]));
    };