<?php
/**
 * User: hameijer
 * Date: 16-3-17
 * Time: 13:40
 */

namespace rikmeijer\Teach;


class Request
{

    public function handle($route, \Psr\Http\Message\ServerRequestInterface $psrRequest, Resources $resources, Response $response)
    {
        foreach ($route->attributes as $attributeIdentifier => $attributeValue) {
            $psrRequest = $psrRequest->withAttribute($attributeIdentifier, $attributeValue);
        }

        if ($route === false) {
            $response->send(404, 'Failure');
        } else {
            call_user_func(\Closure::bind($route->handler, $response, '\rikmeijer\Teach\Response'), $resources, $psrRequest);
        }
    }
}