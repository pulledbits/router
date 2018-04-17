<?php

namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;

class ErrorFactory implements RouteEndPoint
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    static function makeInstance(string $code)
    {
        return new ErrorFactory($code);
    }


    public function respond(ResponseFactory $psrResponseFactory): ResponseInterface
    {
        return $psrResponseFactory->changeStatusCode($this->code)->make('');
    }
}