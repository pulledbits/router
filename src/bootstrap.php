<?php

namespace {
    return function (callable $responseSender): \Psr\Http\Message\ResponseInterface {
        /**
         * @var $resources \rikmeijer\Teach\Resources
         */
        $resources = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

        $request = $resources->request();
        $route = $resources->route($request);

        foreach ($route->attributes as $attributeIdentifier => $attributeValue) {
            $request = $request->withAttribute($attributeIdentifier, $attributeValue);
        }

        $response = new \rikmeijer\Teach\Response($responseSender, $resources);

        if ($route === false) {
            $response->send(404, 'Failure');
        } else {
            switch ($request->getMethod()) {
                case 'GET':
                    call_user_func($route->handler, $resources, $response, $request);
                    break;

                case 'POST':
                    call_user_func($route->handler, $resources, $response, $request);
                    break;

                default:
                    $response->send(405, 'Method not allowed');
                    break;
            }
        }
    };
}