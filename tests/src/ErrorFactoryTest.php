<?php

namespace pulledbits\Router;


class ErrorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeResponse_WhenErrorCode_ExpectMatchingPhraseInResponse()
    {
        $factory = ErrorFactory::makeInstance('403');

        $response = $factory->makeResponse();

        $this->assertEquals('Forbidden', $response->getReasonPhrase());
    }
}
