<?php

$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * @var $matcher \Aura\Router\Matcher
 */
$matcher = $bootstrap();

$route = $matcher->match(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
if ($route === false) {
    http_response_code(404);
} else {
    http_response_code(201);
    call_user_func($route->handler);
}