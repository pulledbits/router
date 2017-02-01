<?php
return function(string $factoryPath) {

    require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    $factory = require $factoryPath;

    return new \ActiveRecord\SQL\Schema($factory, new \PDO('mysql:dbname=teach', 'teach', 'teach', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));

};