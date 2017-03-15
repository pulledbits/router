<?php
return function() : \Aura\Router\Matcher {
    /**
     * @var $bootstrap \rikmeijer\Teach\Resources
     */
    $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

    return $bootstrap->router()->getMatcher();
};