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

    public function handle(callable $responseSender)
    {
        /**
         * @var $route \Aura\Router\Route
         * @var $psrRequest \Psr\Http\Message\ServerRequestInterface
         */
        list($route, $psrRequest) = $this->bootstrap->match();

        if ($route !== false) {
            $handler = $route->handler;
        } else {
            $handler = function (\rikmeijer\Teach\Resources $resources, \Psr\Http\Message\RequestInterface $request) : void {
                $this->send(404, 'Failure');
            };
        }

        $response = new \rikmeijer\Teach\Response($responseSender, function(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
            return $this->bootstrap->response($status, $body);
        });
        $handler = $response->bind($handler);
        call_user_func($handler, $this->bootstrap->resources(), $psrRequest);
    }
};