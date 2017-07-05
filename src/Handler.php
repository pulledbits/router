<?php

namespace pulledbits\Router;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Handler
{
    public function match(ServerRequestInterface $request) : bool;
    public function handleRequest(ServerRequestInterface $request) : ResponseInterface;
}