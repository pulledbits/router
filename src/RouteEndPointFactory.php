<?php

namespace pulledbits\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

interface RouteEndPointFactory
{
    public function matchURI(UriInterface $uri) : bool;
    public function makeRouteEndPointForRequest(ServerRequestInterface $request) : RouteEndPoint;
}