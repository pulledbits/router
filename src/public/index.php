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
    /**
     * @var $response \Psr\Http\Message\ResponseInterface
     */
    $response;
    switch ($request->getMethod()) {
        case 'GET':
            $response = call_user_func_array($route->handler, [$route->attributes, $request->getQueryParams()]);
            break;
        case 'POST':
            $response = call_user_func_array($route->handler, [$route->attributes, $request->getQueryParams(), $request->getParsedBody()]);
            break;

    }

    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $headerIdentifier => $headerValue) {
        header($headerIdentifier . ': ' . $headerValue);
    }
    print $response->getBody();
}