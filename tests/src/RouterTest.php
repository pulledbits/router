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
use Psr\Http\Message\UriInterface;
use pulledbits\View\TemplateInstance;

class RouterTest extends \PHPUnit\Framework\TestCase
{

    public function testRoute_When_ExistingRoute_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([
            new class implements RouteEndPointFactory {
                public function matchURI(UriInterface $uri) : bool {
                    return true;
                }
                public function makeRouteEndPointForRequest(ServerRequestInterface $request): RouteEndPoint {
                    return new class implements RouteEndPoint {
                        public function respond(ResponseFactory $psrResponseFactory) : ResponseInterface {
                            return $psrResponseFactory->make('Hello World!');
                        }
                    };
                }
            }
        ]);

        $response = $router->route($request);

        $this->assertEquals("Hello World!", $response->respond(new ResponseFactory('202'))->getBody());

    }

    public function testRoute_When_NoMatchingRouteExists_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([
            new class implements RouteEndPointFactory {
                public function matchURI(UriInterface $uri) : bool {
                    return false;
                }
                public function makeRouteEndPointForRequest(ServerRequestInterface $request): RouteEndPoint {
                    return new class implements RouteEndPoint {
                        public function respond(ResponseFactory $psrResponseFactory) : ResponseInterface {
                            return $psrResponseFactory->make('');
                        }
                    };
                }
            }
        ]);

        $response = $router->route($request)->respond(new ResponseFactory('200'));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());

    }

    public function testRoute_When_MissingRoute_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([]);

        $response = $router->route($request)->respond(new ResponseFactory('200'));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());

    }
}
