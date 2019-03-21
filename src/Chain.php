<?php


namespace pulledbits\Router;


use Psr\Http\Message\ServerRequestInterface;

class Chain
{
    private $endPoint;
    private $links = [];

    /**
     * Chain constructor.
     */
    public function __construct(RouteEndPoint $endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public function link(Route $route)
    {
        $this->links[] = $route;
    }

    public function handleRequest(ServerRequestInterface $request) : RouteEndPoint
    {
        foreach ($this->links as $link) {
            $this->endPoint = $link->handleRequest($request, $this->endPoint);
        }
        return $this->endPoint;
    }
}