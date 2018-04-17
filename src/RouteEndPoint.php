<?php

namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

interface RouteEndPoint
{
    public function respond(ResponseFactory $psrResponseFactory): ResponseInterface;
}