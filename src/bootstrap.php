<?php
return new class {

    /**
     * @var \rikmeijer\Teach\Bootstrap
     */
    private $bootstrap;

    /**
     * @var \Aura\Router\Route
     */
    private $route;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $psrRequest;

    public function __construct()
    {
        $this->bootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
        list($this->route, $this->psrRequest) = $this->bootstrap->match();
    }

    public function handle(callable $responseSender)
    {
        if ($this->route !== false) {
            $handler = $this->route->handler;
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