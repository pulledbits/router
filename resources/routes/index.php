<?php return function(\Aura\Router\Map $map) {
    $map->get('index', '/', function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response, \Psr\Http\Message\RequestInterface $request) : void {
        $schema = $resources->schema();
        $response->send(200, $resources->phpview('welcome')->capture([
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], [])
        ]));
    });
};