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
        $path = $request->getUri()->getPath();
        foreach ($this->routes as $routeRegularExpression => $handler) {
            if (preg_match('#^' . $routeRegularExpression . '#', $path, $matches) === 1) {
                foreach ($matches as $attributeIdentifier => $attributeValue) {
                    $request = $request->withAttribute($attributeIdentifier, $attributeValue);
                }
                return $handler->handleRequest($request);

            }
        }
        return new Response(404);
    }

}