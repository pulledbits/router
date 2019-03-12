<?php

namespace pulledbits\Router;

class Router
{
    /**
     * @var RouteEndPoint[]
     */
    private $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function addRoute(string $regexp, callable $route)
    {
        $this->routes[$regexp] = $route;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request): RouteEndPoint
    {
        $uri = $request->getUri();
        foreach ($this->routes as $regexp => $responseFactory) {
            $matches = [];
            if (preg_match("#" . $regexp . "#", $uri->getPath(), $matches) === 1) {
                return $responseFactory($request);
            }
        }
        return ErrorFactory::makeInstance(404);
    }

}