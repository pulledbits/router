<?php
return function(callable $responseSender) : \Psr\Http\Message\ResponseInterface {
    /**
     * @var $resources \rikmeijer\Teach\Resources
     */
    $resources = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $request = $resources->request();
    $route = $resources->route($request);

    if ($route === false) {
        $response = $resources->response(404);
    } else {
        switch ($request->getMethod()) {
            case 'GET':
                $response = call_user_func($route->handler, $resources, $route->attributes, $request->getQueryParams());
                break;

            case 'POST':
                $response = call_user_func($route->handler, $resources, $route->attributes, $request->getQueryParams(), $request->getParsedBody());
                break;

            default:
                $response = $resources->response(405);
                break;
        }
    }

    $responseSender($response);
};