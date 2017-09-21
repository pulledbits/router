<?php

namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactory
{
    public function makeResponse() : ResponseInterface;
}