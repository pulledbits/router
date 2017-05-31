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
            '/feedback/(?<contactmomentIdentifier>\d+)/supply' => 'feedback.supply',
            '/feedback/(?<contactmomentIdentifier>\d+)' => 'feedback',
            '/rating/(?<contactmomentIdentifier>\d+)' => 'rating',
            '/contactmoment/import' => 'contactmoment.import',
            '/qr' => 'qr',
            '/' => 'index'
        ]);

        $route = $router->route($this->bootstrap->request());

        if ($route === false) {
            return $this->bootstrap->response(404, 'Failure');
        }

        return $route->execute($this->bootstrap->resources(), new \rikmeijer\Teach\Response(function(int $status, string $body) : \Psr\Http\Message\ResponseInterface {
            return $this->bootstrap->response($status, $body);
        }));
    }
};