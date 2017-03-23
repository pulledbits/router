<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $session = $resources->session();
        return $response->send(200, $resources->phpview('contactmoment/import')->capture([
            'importForm' => function() use ($session) : void {
                $model = 'ICS URL: <input type="text" name="url" />';

                $this->form("post", $session->getCsrfToken()->getValue(), "Importeren", $model);
            }
        ]));
    };