<?php

namespace pulledbits\Router;

class Router
{
    /**
     * @var callable[]
     */
    private $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function addPath(string $regexp, string $responseFactory) {
        $this->addRoute($regexp, require $responseFactory);
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
                foreach ($matches as $matchIdentifier => $match) {
                    $request = $request->withAttribute($matchIdentifier, $match);
                }
                return $responseFactory()->handleRequest($request);
            }
        }
        return ErrorFactory::makeInstance(404);
    }

}