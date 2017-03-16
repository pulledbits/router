<?php

$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$bootstrap(function(\Psr\Http\Message\ResponseInterface $response) {
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $headerIdentifier => $headerValue) {
        header($headerIdentifier . ': ' . $headerValue);
    }
    print $response->getBody();
    exit;
});