<?php

namespace pulledbits\Router;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use pulledbits\Response\Factory;

class ErrorFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeResponse_WhenErrorCode_ExpectMatchingPhraseInResponse()
    {
        $factory = ErrorFactory::makeInstance('403');

        $response = $factory->respond(new class implements ResponseFactory {
            public function make(string $statusCode,string $body): ResponseInterface
            {
                return new Response($statusCode);
            }

            public function makeWithHeaders(string $statusCode,array $headers, string $body): ResponseInterface
            {
            }
        });

        $this->assertEquals('Forbidden', $response->getReasonPhrase());
    }
}
