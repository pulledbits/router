<?php

namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

interface Handler
{
    public function makeResponse() : ResponseInterface;
}