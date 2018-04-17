<?php

namespace pulledbits\Router;

use GuzzleHttp\Psr7\Response;

class ErrorFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeResponse_WhenErrorCode_ExpectMatchingPhraseInResponse()
    {
        $factory = ErrorFactory::makeInstance('403');

        $response = $factory->respond(new Response('200'));

        $this->assertEquals('Forbidden', $response->getReasonPhrase());
    }
}
