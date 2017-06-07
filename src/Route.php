<?php
namespace pulledbits\Router;


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
        array_unshift($arguments, $this->request);
        return call_user_func_array($handler, $arguments);
    }
};