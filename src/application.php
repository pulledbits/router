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
        $router = $this->bootstrap->router([
            '/' => 'index',
            '/qr' => 'qr',
            '/rating/(?<contactmomentIdentifier>\d+)' => 'rating',
            '/contactmoment/import' => 'contactmoment.import',
            '/feedback/(?<contactmomentIdentifier>\d+)' => 'feedback',
            '/feedback/(?<contactmomentIdentifier>\d+)/supply' => 'feedback.supply'
        ]);

        $handler = $router->route($this->bootstrap->request());

        if ($handler === false) {
            return $this->bootstrap->response(404, 'Failure');
        }

        return $handler($this->bootstrap->resources(), new \rikmeijer\Teach\Response(function(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
            return $this->bootstrap->response($status, $body);
        }));
    }
};