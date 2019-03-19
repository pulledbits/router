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
        $reflection = new \ReflectionFunction($routeFactory);
        if ($reflection->hasReturnType() === false) {
            return;
        } elseif ($reflection->getReturnType()->getName() !== Route::class) {
            return;
        }
        $this->routes[$regexp] = $routeFactory;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request): RouteEndPoint
    {
        $uri = $request->getUri();
        foreach ($this->routes as $regexp => $routeFactory) {
            $matches = [];
            if (preg_match("#" . $regexp . "#", $uri->getPath(), $matches) === 1) {
                foreach ($matches as $matchIdentifier => $match) {
                    $request = $request->withAttribute($matchIdentifier, $match);
                }
                return $routeFactory()->handleRequest($request);
            }
        }
        return ErrorFactory::makeInstance(404);
    }

}