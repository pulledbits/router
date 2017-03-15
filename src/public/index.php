<?php

$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * @var $response \Psr\Http\Message\ResponseInterface
 */
$response = $bootstrap();
http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $headerIdentifier => $headerValue) {
    header($headerIdentifier . ': ' . $headerValue);
}
print $response->getBody();