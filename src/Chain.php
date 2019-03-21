<?php


namespace pulledbits\Router;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Chain implements RouteEndPoint
{
    private $links = [];
    private $request;

    /**
     * Chain constructor.
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function link(\Closure $link)
    {
        $this->links[] = $link;
    }

    public function respond(ResponseInterface $response) : ResponseInterface
    {
        $last = function(ServerRequestInterface $request) use ($response) {
            return $response;
        };
        foreach ($this->links as $link) {
            $last = function(ServerRequestInterface $request) use ($link, $last) {
                return $link($request, $last);
            };
        }
        return $last($this->request);
    }
}