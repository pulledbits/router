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

    public function handle() : \Psr\Http\Message\ResponseInterface
    {
        /**
         * @var $route \Aura\Router\Route
         * @var $psrRequest \Psr\Http\Message\ServerRequestInterface
         */
        list($route, $psrRequest) = $this->bootstrap->match();

        if ($route === false) {
            return $this->bootstrap->response(404, 'Failure');
        }

        return call_user_func($route->handler, $psrRequest, new \rikmeijer\Teach\Response(function(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
            return $this->bootstrap->response($status, $body);
        }));
    }
};