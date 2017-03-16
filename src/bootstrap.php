<?php
return function() : \Psr\Http\Message\ResponseInterface {
    /**
     * @var $resources \rikmeijer\Teach\Resources
     */
    $resources = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $request = $resources->request();
    $route = $resources->route($request);

    if ($route === false) {
        return $resources->response(404);
    }

    switch ($request->getMethod()) {
        case 'GET':
            return call_user_func($route->handler, $resources, $route->attributes, $request->getQueryParams());

        case 'POST':
            return call_user_func($route->handler, $resources, $route->attributes, $request->getQueryParams(), $request->getParsedBody());

        default:
            return $resources->response(405);
    }
};