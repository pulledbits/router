<?php

namespace pulledbits\Router;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Matcher
{
    public function matchRequest(ServerRequestInterface $request) : Handler;
}