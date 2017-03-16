<?php

namespace {

    use rikmeijer\Teach\Request;

    return function (callable $responseSender): \Psr\Http\Message\ResponseInterface {
        /**
         * @var $resources \rikmeijer\Teach\Bootstrap
         */
        $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

        $bootstrap->handle($responseSender);
    };
}