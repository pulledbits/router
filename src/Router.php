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

    public function route(\Psr\Http\Message\ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        /**
         * @var $responseFactoryFactory ResponseFactoryFactory
         */
        foreach ($this->routes as $responseFactoryFactory) {
            if ($responseFactoryFactory->matchURI($request->getUri())) {
                return $responseFactoryFactory->makeResponseFactory($request)->makeResponse();
            }
        }
        return new Response(404);
    }

}