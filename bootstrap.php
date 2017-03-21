<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

return new class implements \rikmeijer\Teach\Bootstrap
{
    public function match() : array {
        $routerContainer = new \Aura\Router\RouterContainer();
        $map = $routerContainer->getMap();

        $routes = [];
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . '*.php') as $routeFile) {
            $routeFactory = require $routeFile;
            $routes[] = $routeFactory($map, $this->resources());
        }

        $matcher = $routerContainer->getMatcher();

        $request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
        $route = $matcher->match($request);
        foreach ($route->attributes as $attributeIdentifier => $attributeValue) {
            $request = $request->withAttribute($attributeIdentifier, $attributeValue);
        }
        return [$route, $request];
    }

    public function response(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
        return (new \GuzzleHttp\Psr7\Response($status))->withBody(\GuzzleHttp\Psr7\stream_for($body));
    }

    public function resources() : \rikmeijer\Teach\Resources
    {
        return new \rikmeijer\Teach\Resources(__DIR__ . DIRECTORY_SEPARATOR . 'resources');
    }
};