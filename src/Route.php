<?php
/**
 * User: hameijer
 * Date: 31-5-17
 * Time: 16:18
 */

namespace rikmeijer\Teach;


class Route
{
    private $routeFile;
    private $request;

    public function __construct(string $routeFile, \Psr\Http\Message\RequestInterface $request) {
        $this->routeFile = $routeFile;
        $this->request = $request;
    }

    public function execute(\rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
        $handler = require $this->routeFile;
        return $handler($this->request, $resources, $response);
    }
};