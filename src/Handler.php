<?php

namespace pulledbits\Router;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Handler
{
    public function handleRequest(ServerRequestInterface $request) : ResponseInterface;
}