<?php

$bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$schema = $bootstrap();

var_dump($schema->read('les', ['id'], []));
