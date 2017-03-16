<?php
namespace rikmeijer\Teach;

use Psr\Http\Message\ResponseInterface;

class Response
{
    private $responseSender;
    private $responseFactory;

    public function __construct(callable $responseSender, callable $responseFactory)
    {
        $this->responseSender = $responseSender;
        $this->responseFactory = $responseFactory;
    }

    private function makeResponse(int $status, string $body) : ResponseInterface
    {
        return call_user_func($this->responseFactory, $status, $body);
    }

    public function bind(callable $handler) : callable
    {
        return \Closure::bind($handler, $this, __CLASS__);
    }

    public function send(int $status, string $body) : void
    {
        call_user_func($this->responseSender, $this->makeResponse($status, $body));
    }

    public function sendWithHeaders(int $status, array $headers, string $body) : void
    {
        $response = $this->makeResponse($status, $body);
        foreach ($headers as $headerIdentifier => $headerValue) {
            $response = $response->withHeader($headerIdentifier, $headerValue);
        }
        call_user_func($this->responseSender, $response);

    }
}