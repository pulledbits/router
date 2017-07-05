<?php

namespace pulledbits\Router;

use GuzzleHttp\Psr7\Response;

class Router
{
    /**
     * @var Handler[]
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        foreach ($this->routes as $routeRegularExpression => $matcher) {
            $handler = $matcher->matchRequest($request);
            return $handler->handleRequest($request);
        }
        return new Response(404);
    }

}