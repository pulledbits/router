<?php
/**
 * User: hameijer
 * Date: 23-3-17
 * Time: 10:06
 */

namespace rikmeijer\Teach;


use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var string
     */
    private $routesPath;

    public function __construct(array $routes, string $routesPath)
    {
        uksort($routes, function($a,$b){
            return strlen($b) - strlen($a);
        });
        $this->routes = $routes;
        $this->routesPath = $routesPath;
    }

    public function route(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();
        $routeFile = null;
        foreach ($this->routes as $routeRegularExpression => $routeIdentifier) {
            if (preg_match('#^' . $routeRegularExpression . '#', $path, $matches) === 1) {
                $routeFile = $this->routesPath . DIRECTORY_SEPARATOR . $request->getMethod() . DIRECTORY_SEPARATOR . $routeIdentifier . '.php';
                foreach ($matches as $attributeIdentifier => $attributeValue) {
                    $request = $request->withAttribute($attributeIdentifier, $attributeValue);
                }

                return function (\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) use ($routeFile, $request) : \Psr\Http\Message\ResponseInterface {
                    $handler = require $routeFile;
                    return $handler($request, $resources, $response);
                };
            }
        }
        return false;
    }

}