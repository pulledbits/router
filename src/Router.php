<?php

namespace pulledbits\Router;

class Router
{
    /**
     * @var RouteEndPoint[]
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request): RouteEndPoint
    {
        /**
         * @var $responseFactoryFactory RouteEndPointFactory
         */
        foreach ($this->routes as $responseFactoryFactory) {
            if ($responseFactoryFactory->matchURI($request->getUri())) {
                return $responseFactoryFactory->makeRouteEndPointForRequest($request);
            }
        }
        return ErrorFactory::makeInstance(404);
    }

}