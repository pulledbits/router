<?php
namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactory
{
    public function make(string $statusCode, string $body) : ResponseInterface;

    public function makeWithHeaders(string $statusCode, array $headers, string $body) : ResponseInterface;
}