<?php

$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * @var $matcher \Aura\Router\Matcher
 */
$matcher = $bootstrap();

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

$route = $matcher->match($request);
if ($route === false) {
    http_response_code(404);
} else {
    switch ($request->getMethod()) {
        case 'GET':
            http_response_code(200);
            print call_user_func_array($route->handler, [$route->attributes, $request->getQueryParams()]);
            break;
        case 'POST':
            http_response_code(201);
            print call_user_func_array($route->handler, [$route->attributes, $request->getQueryParams(), $request->getParsedBody()]);
            break;

    }
}