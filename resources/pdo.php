<?php
$config = require __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
return new \PDO($config['DB_CONNECTION'] . ':host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_DATABASE'],
    $config['DB_USERNAME'], $config['DB_PASSWORD'],
    array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));