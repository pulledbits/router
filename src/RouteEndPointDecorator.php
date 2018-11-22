<?php
namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

class RouteEndPointDecorator implements RouteEndPoint
{
    private $wrappedEndPoint;

    public function __construct(RouteEndPoint $wrappedEndPoint)
    {
        $this->wrappedEndPoint = $wrappedEndPoint;
    }

    public function respond(ResponseInterface $psrResponse): ResponseInterface
    {
        return $this->wrappedEndPoint->respond($psrResponse);
    }
}