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
        $this->routes = $routes;
        $this->routesPath = $routesPath;
    }

    public function route(ServerRequestInterface $request) : Route
    {
        $path = $request->getUri()->getPath();
        $routeFile = null;
        foreach ($this->routes as $routeRegularExpression => $routeIdentifier) {
            if (preg_match('#^' . $routeRegularExpression . '#', $path, $matches) === 1) {
                $routeFile = $this->routesPath . DIRECTORY_SEPARATOR . $request->getMethod() . DIRECTORY_SEPARATOR . $routeIdentifier . '.php';
                foreach ($matches as $attributeIdentifier => $attributeValue) {
                    $request = $request->withAttribute($attributeIdentifier, $attributeValue);
                }

                return new Route($routeFile, $request);

            }
        }
        return false;
    }

}