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

                return new class($routeFile, $request) {

                    private $routeFile;
                    private $request;

                    public function __construct(string $routeFile, \Psr\Http\Message\RequestInterface $request) {
                        $this->routeFile = $routeFile;
                        $this->request = $request;
                    }

                    public function __invoke(\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
                        $handler = require $this->routeFile;
                        return $handler($this->request, $resources, $response);
                    }
                };

            }
        }
        return false;
    }

}