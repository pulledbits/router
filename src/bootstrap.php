<?php return function() {

    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    return $bootstrap(__DIR__ . DIRECTORY_SEPARATOR . 'gen' . DIRECTORY_SEPARATOR . 'factory.php');

};