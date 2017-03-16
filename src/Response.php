<?php
namespace rikmeijer\Teach;

class Response
{
    private $responseSender;
    private $bootstrap;

    public function __construct(callable $responseSender, \rikmeijer\Teach\Bootstrap $bootstrap)
    {
        $this->responseSender = $responseSender;
        $this->bootstrap = $bootstrap;
    }

    public function send(int $status, string $body) : void
    {
        call_user_func($this->responseSender, $this->bootstrap->response($status, $body));
    }

    public function sendWithHeaders(int $status, array $headers, string $body) : void
    {
        $response = $this->bootstrap->response($status, $body);
        foreach ($headers as $headerIdentifier => $headerValue) {
            $response = $response->withHeader($headerIdentifier, $headerValue);
        }
        call_user_func($this->responseSender, $response);

    }
}