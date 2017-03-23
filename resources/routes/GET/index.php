<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        return $response->send(200, $resources->phpview('welcome')->capture([
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], [])
        ]));
};