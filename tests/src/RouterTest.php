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
use Psr\Http\Message\ResponseInterface;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testRoute_When_ExistingRoute_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([
            new class implements ResponseFactoryFactory {
                public function matchRequest(ServerRequestInterface $request) : bool {
                    return true;
                }
                public function makeResponseFactory(ServerRequestInterface $request): ResponseFactory {
                    return new class implements ResponseFactory {
                        public function makeResponse() : ResponseInterface {
                            return new Response(202, [],"Hello World!");
                        }
                    };
                }
            }
        ]);

        $response = $router->route($request);

        $this->assertEquals("Hello World!", $response->getBody());

    }

    public function testRoute_When_NoMatchingRouteExists_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([
            new class implements ResponseFactoryFactory {
                public function matchRequest(ServerRequestInterface $request) : bool {
                    return false;
                }
                public function makeResponseFactory(ServerRequestInterface $request): ResponseFactory {
                    return new class implements ResponseFactory {
                        public function makeResponse() : ResponseInterface {
                            return new class extends Response {};
                        }
                    };
                }
            }
        ]);

        $response = $router->route($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());

    }

    public function testRoute_When_MissingRoute_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([]);

        $response = $router->route($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());

    }
}
