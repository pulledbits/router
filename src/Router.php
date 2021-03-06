<?php

namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

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

    public function addPath(string $regexp, string $routeFactoryPath)
    {
        $this->addRoute($regexp, require $routeFactoryPath);
    }

    public function addRoute(string $regexp, $routeFactory): void
    {
        $this->routes[$regexp] = $routeFactory;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request): RouteEndPoint
    {
        $uri = $request->getUri();
        foreach ($this->routes as $regexp => $routeFactory) {
            if (preg_match("/^(\w+|\([\w\|]+\))\:/", $regexp, $matches) === 0) {
                $regexp = '\w+:' . $regexp;
            }

            $matches = [];
            if (preg_match("#" . $regexp . "#", $request->getMethod() . ':' . $uri->getPath(), $matches) === 1) {
                foreach ($matches as $matchIdentifier => $match) {
                    $request = $request->withAttribute($matchIdentifier, $match);
                }

                if ($routeFactory instanceof \Closure) {
                    $chain = new Chain($request);
                    $reflection = new \ReflectionFunction($routeFactory);
                    if ($reflection->hasReturnType() === false || $reflection->getReturnType()->getName() === 'void') {
                        $routeFactory($chain);
                    } elseif ($reflection->getReturnType()->getName() === ResponseInterface::class) {
                        $chain->link($routeFactory);
                    }
                    return new ChainedEndPoint($chain);
                } elseif ($routeFactory instanceof RouteEndPoint) {
                    return $routeFactory;
                } elseif ($routeFactory instanceof Route) {
                    return $routeFactory->handleRequest($request);
                }
            }
        }
        return ErrorFactory::makeInstance(404);
    }

}