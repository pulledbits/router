<?php
$resources = require dirname(__DIR__) . DIRECTORY_SEPARATOR;

return [
    'driver' => 'pdo_mysql',
    'host' => $resources['DB_HOST'],
    'dbname' => $resources['DB_DATABASE'],
    'user' =>  $resources['DB_USERNAME'],
    'password' => $resources['DB_PASSWORD']
];