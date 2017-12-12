<?php

namespace pulledbits\Router;

use GuzzleHttp\Psr7\Response;

class Router
{
    /**
     * @var ResponseFactory[]
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request) : ResponseFactory
    {
        /**
         * @var $responseFactoryFactory ResponseFactoryFactory
         */
        foreach ($this->routes as $responseFactoryFactory) {
            if ($responseFactoryFactory->matchURI($request->getUri())) {
                return $responseFactoryFactory->makeResponseFactory($request);
            }
        }
        return ErrorFactory::makeInstance(404);
    }

}