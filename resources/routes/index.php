<?php return function(\Aura\Router\Map $map) {
    $map->get('index', '/', function (\rikmeijer\Teach\Resources $bootstrap, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();
        return $bootstrap->response(200, $bootstrap->phpview('welcome')->capture([
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], [])
        ]));
    });
};