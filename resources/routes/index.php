<?php return function(\Aura\Router\Map $map) {
    $map->get('index', '/', function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Request $request) : void {
        $schema = $resources->schema();
        $request->respond(200, $resources->phpview('welcome')->capture([
            'modules' => $schema->read('module', [], []),
            'contactmomenten' => $schema->read('contactmoment_vandaag', [], [])
        ]));
    });
};