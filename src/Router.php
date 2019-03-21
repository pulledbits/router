<?php

namespace pulledbits\Router;

class Router
{
    /**
     * @var callable[]
     */
    private $routes = [];

    public function __construct(array $routeFactories)
    {
        $this->routes = $routeFactories;
    }

    public function addPath(string $regexp, string $routeFactoryPath) {
        $this->addRoute($regexp, require $routeFactoryPath);
    }

    public function addRoute(string $regexp, callable $routeFactory) : void
    {
        $this->routes[$regexp] = $routeFactory;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request): RouteEndPoint
    {
        $uri = $request->getUri();
        $endPoint = ErrorFactory::makeInstance(404);
        foreach ($this->routes as $regexp => $routeFactory) {
            $matches = [];
            if (preg_match("#" . $regexp . "#", $uri->getPath(), $matches) === 1) {
                $chain = new Chain($endPoint);
                ($this->routes[$regexp])($chain);

                foreach ($matches as $matchIdentifier => $match) {
                    $request = $request->withAttribute($matchIdentifier, $match);
                }
                return $chain->handleRequest($request);
            }
        }
        return $endPoint;
    }

}