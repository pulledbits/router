<?php

namespace pulledbits\Router;


use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var array
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(ServerRequestInterface $request) : Route
    {
        $path = $request->getUri()->getPath();
        $routeFile = null;
        foreach ($this->routes as $routeRegularExpression => $routeFile) {
            if (preg_match('#^' . $routeRegularExpression . '#', $request->getMethod() . ':' . $path, $matches) === 1) {
                foreach ($matches as $attributeIdentifier => $attributeValue) {
                    $request = $request->withAttribute($attributeIdentifier, $attributeValue);
                }

                return new Route($routeFile, $request);

            }
        }
        return false;
    }

}