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

    public function execute(array $arguments) : \Psr\Http\Message\ResponseInterface {
        $handler = require $this->routeFile;
        return call_user_func_array($handler, $this->request, $arguments);
    }
};