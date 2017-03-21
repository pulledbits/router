<?php
namespace rikmeijer\Teach;

use Psr\Http\Message\ResponseInterface;

class Response
{
    private $responseFactory;

    public function __construct(callable $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    private function makeResponse(int $status, string $body) : ResponseInterface
    {
        return call_user_func($this->responseFactory, $status, $body);
    }

    public function send(int $status, string $body) : ResponseInterface
    {
        return $this->makeResponse($status, $body);
    }

    public function sendWithHeaders(int $status, array $headers, string $body) : ResponseInterface
    {
        $response = $this->makeResponse($status, $body);
        foreach ($headers as $headerIdentifier => $headerValue) {
            $response = $response->withHeader($headerIdentifier, $headerValue);
        }
        return $response;

    }
}