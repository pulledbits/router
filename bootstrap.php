<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

return new class implements \rikmeijer\Teach\Bootstrap
{
    public function router(array $routes) : \rikmeijer\Teach\Router {
        return new \rikmeijer\Teach\Router($routes, __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'routes');
    }

    public function request() : \Psr\Http\Message\ServerRequestInterface
    {
        return \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
    }

    public function response(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
        return (new \GuzzleHttp\Psr7\Response($status))->withBody(\GuzzleHttp\Psr7\stream_for($body));
    }

    public function resources() : \rikmeijer\Teach\Resources
    {
        return new \rikmeijer\Teach\Resources(__DIR__ . DIRECTORY_SEPARATOR . 'resources');
    }
};