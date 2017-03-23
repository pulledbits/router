<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

return new class implements \rikmeijer\Teach\Bootstrap
{
    public function match(array $routes) : array {
        uksort($routes, function($a,$b){
            return strlen($b) - strlen($a);
        });

        $request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

        $path = $request->getUri()->getPath();
        $routeFile = null;
        foreach ($routes as $routeRegularExpression => $routeIdentifier) {
            if (preg_match('#^' . $routeRegularExpression . '#', $path, $matches) === 1) {
                $routeFile = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . $request->getMethod() . DIRECTORY_SEPARATOR . $routeIdentifier . '.php';
                foreach ($matches as $attributeIdentifier => $attributeValue) {
                    $request = $request->withAttribute($attributeIdentifier, $attributeValue);
                }
                break;
            }
        }
        if ($routeFile === null) {
            return [false, $request];
        }

        $resources = $this->resources();
        return [require $routeFile, $request];
    }

    public function response(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
        return (new \GuzzleHttp\Psr7\Response($status))->withBody(\GuzzleHttp\Psr7\stream_for($body));
    }

    public function resources() : \rikmeijer\Teach\Resources
    {
        return new \rikmeijer\Teach\Resources(__DIR__ . DIRECTORY_SEPARATOR . 'resources');
    }
};