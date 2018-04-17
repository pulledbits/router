<?php

namespace pulledbits\Router;

class ErrorFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeResponse_WhenErrorCode_ExpectMatchingPhraseInResponse()
    {
        $factory = ErrorFactory::makeInstance('403');

        $response = $factory->respond(new ResponseFactory('200'));

        $this->assertEquals('Forbidden', $response->getReasonPhrase());
    }
}
