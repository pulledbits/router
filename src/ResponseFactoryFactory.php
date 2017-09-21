<?php

namespace pulledbits\Router;

use Psr\Http\Message\ServerRequestInterface;

interface ResponseFactoryFactory
{
    public function matchRequest(ServerRequestInterface $request) : bool;
    public function makeResponseFactory(ServerRequestInterface $request) : ResponseFactory;
}