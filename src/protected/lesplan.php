<?php
if (array_key_exists('contactmoment', $_GET) === false) {
    http_response_code(400);
    exit();
}

/**
 *
 * @var \ApplicationBootstrap $applicationBootstrap
 */
$applicationBootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$response = $applicationBootstrap->handleRequest($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

foreach ($response->getHeaders() as $name => $values) {
    header($name . ": " . implode(", ", $values));
}
$body = $response->getBody();
echo $body->getContents();