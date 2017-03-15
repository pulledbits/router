<?php
return function() : \Psr\Http\Message\ResponseInterface {
    /**
     * @var $bootstrap \rikmeijer\Teach\Resources
     */
    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $request = $bootstrap->request();
    $route = $bootstrap->route($request);

    if ($route === false) {
        return $bootstrap->response(404);
    }

    switch ($request->getMethod()) {
        case 'GET':
            return call_user_func_array($route->handler, [$route->attributes, $request->getQueryParams()]);

        case 'POST':
            return call_user_func_array($route->handler, [$route->attributes, $request->getQueryParams(), $request->getParsedBody()]);

        default:
            return $bootstrap->response(405);
    }
};