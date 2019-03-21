<?php


namespace pulledbits\Router;


use Psr\Http\Message\ResponseInterface;

class ChainedEndPoint implements RouteEndPoint
{
    private $chain;

    public function __construct(Chain $chain)
    {
        $this->chain = $chain;
    }

    public function respond(ResponseInterface $response): ResponseInterface
    {
        return $this->chain->respond($response);
    }
}