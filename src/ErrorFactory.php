<?php


namespace pulledbits\Router;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ErrorFactory implements ResponseFactory
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


    public function makeResponse(): ResponseInterface
    {
        return new Response($this->code);
    }
}