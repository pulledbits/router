<?php


namespace pulledbits\Router;


use Psr\Http\Message\ResponseInterface;

class Route
{
    private $endPoint;

    public function __construct(RouteEndPoint $endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public function respond(ResponseInterface $response): ResponseInterface
    {
        return $this->endPoint->respond($response);
    }
}