<?php
$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$resources = require __DIR__ . DIRECTORY_SEPARATOR . 'resources.php';

return [
    'driver' => 'pdo_mysql',
    'host' => $resources['DB_HOST'],
    'dbname' => $resources['DB_DATABASE'],
    'user' =>  $resources['DB_USERNAME'],
    'password' => $resources['DB_PASSWORD']
];