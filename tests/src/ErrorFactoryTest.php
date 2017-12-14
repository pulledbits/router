<?php

namespace pulledbits\Router;


use pulledbits\Response\Factory;

class ErrorFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeResponse_WhenErrorCode_ExpectMatchingPhraseInResponse()
    {
        $factory = ErrorFactory::makeInstance('403');

        $response = $factory->respond(new Factory());

        $this->assertEquals('Forbidden', $response->getReasonPhrase());
    }
}
