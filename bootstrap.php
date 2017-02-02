<?php
return function(string $factoryPath) : \ActiveRecord\SQL\Schema {
    require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    /**
     * @var $factory \ActiveRecord\RecordFactory
     */
    $factory = require $factoryPath;
    return new \ActiveRecord\SQL\Schema($factory, new \PDO($_ENV['DB_CONNECTION'] . ':dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
};