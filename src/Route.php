<?php


namespace pulledbits\Router;


use Psr\Http\Message\ServerRequestInterface;

interface Route
{
    public function handleRequest(ServerRequestInterface $request, RouteEndPoint $currentEndPoint) : RouteEndPoint;
}