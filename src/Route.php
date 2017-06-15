<?php
namespace pulledbits\Router;


class Route
{
    private $handler;
    private $request;

    public function __construct(callable $handler, \Psr\Http\Message\RequestInterface $request) {
        $this->handler = $handler;
        $this->request = $request;
    }

    public function execute(array $arguments) : \Psr\Http\Message\ResponseInterface {
        array_unshift($arguments, $this->request);
        return call_user_func_array($this->handler, $arguments);
    }
};