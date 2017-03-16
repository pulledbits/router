<?php

$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';


$bootstrap->handle(function(\Psr\Http\Message\ResponseInterface $response) {
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $headerIdentifier => $headerValue) {
        header($headerIdentifier . ': ' . implode(', ', $headerValue));
    }
    print $response->getBody();
    exit;
});