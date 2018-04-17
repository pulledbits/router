<?php

namespace pulledbits\Router;

class ResponseFactory
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function changeStatusCode(string $code): ResponseFactory
    {
        return new static($code);
    }

    public function makeWithHeaders(array $headers, string $body): \Psr\Http\Message\ResponseInterface
    {
        $response = $this->make($body);
        foreach ($headers as $headerIdentifier => $headerValue) {
            $response = $response->withHeader($headerIdentifier, $headerValue);
        }
        return $response;
    }

    public function make(string $body): \Psr\Http\Message\ResponseInterface
    {
        $response = (new \GuzzleHttp\Psr7\Response($this->code))->withBody(\GuzzleHttp\Psr7\stream_for($body));
        $finfo = new \finfo(FILEINFO_MIME);
        return $response->withHeader('Content-Type', $finfo->buffer($body));
    }

    public function makeWithTemplate(\pulledbits\View\Renderable $templateInstance): \Psr\Http\Message\ResponseInterface
    {
        return $this->make($templateInstance->capture());
    }
}