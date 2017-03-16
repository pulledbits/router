<?php
return new class {

    /**
     * @var \rikmeijer\Teach\Bootstrap
     */
    private $bootstrap;

    /**
     * @var \Aura\Router\Matcher
     */
    private $matcher;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $psrRequest;

    public function __construct()
    {
        $this->bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
        $this->matcher = $this->bootstrap->matcher();
        $this->psrRequest = $this->bootstrap->request();
    }

    public function handle(callable $responseSender)
    {
        $route = $this->matcher->match($this->psrRequest);
        foreach ($route->attributes as $attributeIdentifier => $attributeValue) {
            $this->psrRequest = $this->psrRequest->withAttribute($attributeIdentifier, $attributeValue);
        }

        if ($route !== false) {
            $handler = $route->handler;
        } else {
            $handler = function (\rikmeijer\Teach\Resources $resources, \Psr\Http\Message\RequestInterface $request) : void {
                $this->send(404, 'Failure');
            };
        }

        $response = new \rikmeijer\Teach\Response($responseSender, $this->bootstrap->responseFactory());
        $handler = $response->bind($handler);
        call_user_func($handler, $this->bootstrap->resources(), $this->psrRequest);
    }
};