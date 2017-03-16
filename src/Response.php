<?php
namespace rikmeijer\Teach;

class Response
{
    private $responseSender;
    private $resources;

    public function __construct(callable $responseSender, \rikmeijer\Teach\Resources $resources)
    {
        $this->responseSender = $responseSender;
        $this->resources = $resources;
    }

    public function send(int $status, string $body) : void
    {
        call_user_func($this->responseSender, $this->resources->response($status, $body));
    }

    public function sendWithHeaders(int $status, array $headers, string $body) : void
    {
        $response = $this->resources->response($status, $body);
        foreach ($headers as $headerIdentifier => $headerValue) {
            $response = $response->withHeader($headerIdentifier, $headerValue);
        }
        call_user_func($this->responseSender, $response);

    }
}