<?php
/**
 * User: hameijer
 * Date: 7-6-17
 * Time: 11:38
 */

namespace pulledbits\Router;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testRoute_When_ExistingRoute_Expect_RouteReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([
            "/hello/world" => function () {
                return new Response(202, [],"Hello World!");
            }
        ], sys_get_temp_dir());

        $response = $router->route($request);

        $this->assertEquals("Hello World!", $response->getBody());

    }

}
