<?php

namespace {

    use rikmeijer\Teach\Request;

    return function (callable $responseSender): \Psr\Http\Message\ResponseInterface {
        /**
         * @var $resources \rikmeijer\Teach\Bootstrap
         */
        $bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

        $psrRequest = $bootstrap->request();
        $route = $bootstrap->route($psrRequest);

        foreach ($route->attributes as $attributeIdentifier => $attributeValue) {
            $psrRequest = $psrRequest->withAttribute($attributeIdentifier, $attributeValue);
        }

        $request = new Request($psrRequest, $bootstrap->response($responseSender));
        $request->handle($route, $bootstrap->resources());
    };
}