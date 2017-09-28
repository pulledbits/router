<?php

namespace pulledbits\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

interface ResponseFactoryFactory
{
    public function matchURI(UriInterface $uri) : bool;
    public function makeResponseFactory(ServerRequestInterface $request) : ResponseFactory;
}