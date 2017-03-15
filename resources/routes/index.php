<?php return function(\Aura\Router\Map $map) {
    $map->get('index', '/', function (\rikmeijer\Teach\Resources $resources, array $attributes, array $query) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        return $resources->response(200, $resources->phpview('welcome')->capture([
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], [])
        ]));
    });
};