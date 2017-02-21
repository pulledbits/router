<?php
$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

return [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_DATABASE'],
    'user' =>  $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD']
];