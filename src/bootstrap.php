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

    public function handle()
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

        $response = call_user_func($handler, $psrRequest, new \rikmeijer\Teach\Response(function(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
            return $this->bootstrap->response($status, $body);
        }));

        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $headerIdentifier => $headerValue) {
            header($headerIdentifier . ': ' . implode(', ', $headerValue));
        }
        print $response->getBody();
        exit;
    }
};