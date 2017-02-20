<?php
return new class {

    public function __construct() {

        require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();
    }

    public function schema() : \ActiveRecord\SQL\Schema {
        /**
         * @var $factory \ActiveRecord\RecordFactory
         */
        $factory = require __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'activerecord' . DIRECTORY_SEPARATOR . 'factory.php';
        return new \ActiveRecord\SQL\Schema($factory, new \PDO($_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
    }

};