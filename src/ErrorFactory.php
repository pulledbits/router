<?php
namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;
use pulledbits\Response\Factory;

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


    public function respond(Factory $psrResponseFactory): ResponseInterface
    {
        return $psrResponseFactory->make($this->code, '');
    }
}