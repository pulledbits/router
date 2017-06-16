<?php

namespace pulledbits\Router;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Handler
{
    public function handleRequest(RequestInterface $request) : ResponseInterface;
}