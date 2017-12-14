<?php

namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;
use pulledbits\Response\Factory;

interface RouteEndPoint
{
    public function respond(Factory $psrResponseFactory) : ResponseInterface;
}