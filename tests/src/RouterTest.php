<?php
/**
 * User: hameijer
 * Date: 7-6-17
 * Time: 11:38
 */

namespace pulledbits\Router;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use function GuzzleHttp\Psr7\stream_for;
use GuzzleHttp\Psr7\Uri;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class RouterTest extends \PHPUnit\Framework\TestCase
{

    public function testRoute_When_ExistingRoute_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router(["/hello/world" => function() : Route
            {
                return new class implements Route {
                    public function handleRequest(ServerRequestInterface $request): RouteEndPoint
                    {
                        return new class implements RouteEndPoint
                        {
                            public function respond(ResponseInterface $psrResponse): ResponseInterface
                            {
                                return $psrResponse->withBody(stream_for("Hello World!"));
                            }
                        };
                    }
                };
            }
        ]);

        $response = $router->route($request);

        $this->assertEquals("Hello World!", $response->respond(new Response('202'))->getBody());

    }

    public function testAddPath_When_ExistingRoute_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $file = tempnam(sys_get_temp_dir(), 'route_');
        file_put_contents($file, '<?php return function() : pulledbits\Router\Route
        {
            return new class implements pulledbits\Router\Route {
                public function handleRequest(Psr\Http\Message\ServerRequestInterface $request): pulledbits\Router\RouteEndPoint
                {
                    return new class implements pulledbits\Router\RouteEndPoint
                    {
                        public function respond(Psr\Http\Message\ResponseInterface $psrResponse): Psr\Http\Message\ResponseInterface
                        {
                            return $psrResponse->withBody(GuzzleHttp\Psr7\stream_for("Hello World!"));
                        }
                    };
                }
            };
        };');

        $router = new Router([]);
        $router->addPath("/hello/world", $file);

        $response = $router->route($request);

        $this->assertEquals("Hello World!", $response->respond(new Response('202'))->getBody());

        unlink($file);
    }



    public function testRoute_When_ExistingRouteAndAttriubtes_Expect_ResponseWithAttributesReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/wooorld"));

        $router = new Router(["/hello/(?<where>\w+)" => function() : Route
        {
            return new class implements Route {
                public function handleRequest(ServerRequestInterface $request): RouteEndPoint
                {
                    return new class($request->getAttribute('where')) implements RouteEndPoint
                    {
                        private $where;
                        public function __construct(string $where)
                        {
                            $this->where = $where;
                        }

                        public function respond(ResponseInterface $psrResponse): ResponseInterface
                        {
                            return $psrResponse->withBody(stream_for('Hello ' . $this->where . '!'));
                        }
                    };
                }
            };
        }
        ]);

        $response = $router->route($request);

        $this->assertEquals("Hello wooorld!", $response->respond(new Response('202'))->getBody());

    }


    public function testRoute_When_NoMatchingRouteExists_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router(["/hello/worl$" => function(): Route
            {
                return new class implements Route
                {
                    public function handleRequest(ServerRequestInterface $request): RouteEndPoint
                    {
                        return new class implements RouteEndPoint
                        {
                            public function respond(ResponseInterface $psrResponseFactory): ResponseInterface
                            {
                                return $psrResponseFactory->make('');
                            }
                        };
                    }
                };
            }
        ]);

        $response = $router->route($request)->respond(new Response('200'));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());

    }

    public function testRoute_When_MissingRoute_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([]);

        $response = $router->route($request)->respond(new Response('200'));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getReasonPhrase());

    }

    public function testAddRoute_When_MatchingRouteExists_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router([]);
        $router->addRoute("/hello/world", function(): Route  {
            return new class implements Route
            {
                public function handleRequest(ServerRequestInterface $request): RouteEndPoint
                {
                    return new class implements RouteEndPoint
                    {
                        public function respond(ResponseInterface $psrResponseFactory): ResponseInterface
                        {
                            return $psrResponseFactory->withBody(stream_for('Hello'));
                        }
                    };
                }
            };
        });

        $response = $router->route($request)->respond(new Response('200'));

        $this->assertEquals('200', $response->getStatusCode());
        $this->assertEquals("Hello", $response->getBody()->getContents());
    }
}
