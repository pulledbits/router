<?php return function() : \Aura\Router\Matcher {

    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $schema = $bootstrap(__DIR__ . DIRECTORY_SEPARATOR . 'gen' . DIRECTORY_SEPARATOR . 'factory.php');

    $routerContainer = new \Aura\Router\RouterContainer();
    $map = $routerContainer->getMap();

    return $routerContainer->getMatcher();
};