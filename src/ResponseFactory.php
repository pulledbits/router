<?php
namespace pulledbits\Router;

use Psr\Http\Message\ResponseInterface;
use pulledbits\View\TemplateInstance;

interface ResponseFactory
{
    public function make(string $statusCode, string $body) : ResponseInterface;

    public function makeWithHeaders(string $statusCode, array $headers, string $body) : ResponseInterface;

    public function makeWithTemplate(string $statusCode, TemplateInstance $templateInstance) : ResponseInterface;
}