<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
        return $response->send(200, $resources->phpview()->capture('contactmoment/import', [
            'importForm' => function() : void {
                $model = 'ICS URL: <input type="text" name="url" />';

                $this->form("post", "Importeren", $model);
            }
        ]));
    };