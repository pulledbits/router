<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);
        return $response->send(200, $resources->phpview()->capture('feedback', [
            'contactmoment' => $contactmoment
        ]));
    };