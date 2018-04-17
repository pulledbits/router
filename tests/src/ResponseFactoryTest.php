<?php

namespace pulledbits\Router;

use pulledbits\View\Renderable;

class ResponseFactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testMake()
    {
        $object = new ResponseFactory('200');
        $response = $object->make('Hello');
        $this->assertEquals('200', $response->getStatusCode());
        $this->assertEquals('Hello', $response->getBody());
    }

    public function testChangeStatusCode()
    {
        $object = new ResponseFactory('200');
        $response = $object->changeStatusCode('201')->make('Hello');
        $this->assertEquals('201', $response->getStatusCode());
    }

    public function testMakeWithHeaders()
    {
        $object = new ResponseFactory('200');
        $response = $object->makeWithHeaders(['Content-Type' => 'text/plain'], 'Hello');
        $this->assertEquals('200', $response->getStatusCode());
        $this->assertEquals('text/plain', $response->getHeader('Content-Type')[0]);
    }

    public function testMakeWithTemplate()
    {
        $object = new ResponseFactory('200');
        $response = $object->makeWithTemplate(new class implements Renderable
        {
            public function capture(): string
            {
                return 'Hello';
            }
        });
        $this->assertEquals('200', $response->getStatusCode());
        $this->assertEquals('Hello', $response->getBody());
    }
}
