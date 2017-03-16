<?php
return new class {

    /**
     * @var \rikmeijer\Teach\Bootstrap
     */
    private $bootstrap;

    public function __construct()
    {
        $this->bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
    }

    public function response(callable $responseSender) : \rikmeijer\Teach\Response {
        return new \rikmeijer\Teach\Response($responseSender, $this->bootstrap->responseFactory());
    }

    public function handle(callable $responseSender)
    {
        $psrRequest = $this->bootstrap->request();
        $route = $this->bootstrap->route($psrRequest);

        foreach ($route->attributes as $attributeIdentifier => $attributeValue) {
            $psrRequest = $psrRequest->withAttribute($attributeIdentifier, $attributeValue);
        }

        if ($route !== false) {
            $handler = $route->handler;
        } else {
            $handler = function (\rikmeijer\Teach\Resources $resources, \Psr\Http\Message\RequestInterface $request) : void {
                $this->send(404, 'Failure');
            };
        }

        $response = $this->response($responseSender);
        $handler = $response->bind($handler);
        call_user_func($handler, $this->bootstrap->resources(), $psrRequest);
    }
};