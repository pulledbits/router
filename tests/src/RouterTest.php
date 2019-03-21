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

class RouterTest extends \PHPUnit\Framework\TestCase
{

    public function testRoute_When_ExistingRouteEndPoint_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router(["/hello/world" => new class implements RouteEndPoint
        {
            public function respond(ResponseInterface $psrResponse): ResponseInterface
            {
                return $psrResponse->withBody(stream_for("Hello World!"));
            }
        }
        ]);

        $route = $router->route($request);

        $this->assertEquals("Hello World!", $route->respond(new Response('202'))->getBody());

    }
    public function testRoute_When_ExistingRoute_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router(["/hello/world" => new class implements Route
        {
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
        }]);

        $route = $router->route($request);

        $this->assertEquals("Hello World!", $route->respond(new Response('202'))->getBody());
    }

        public function testRoute_When_ExistingRouteMatchesMethod_Expect_ResponseReturned()
    {
        $request = new ServerRequest('POST', new Uri("/post/hello/world"));

        $router = new Router(["POST:/post/hello/world" => new class implements RouteEndPoint
        {
            public function respond(ResponseInterface $psrResponse): ResponseInterface
            {
                return $psrResponse->withBody(stream_for("Hello World!"));
            }
        }
        ]);

        $route = $router->route($request);

        $this->assertEquals("Hello World!", $route->respond(new Response('201'))->getBody()->getContents());

    }

    public function testRoute_When_ExistingRouteMatchesMethods_Expect_ResponseReturned()
    {
        $router = new Router(["(GET|POST):/hello/world" => new class implements RouteEndPoint
        {
            public function respond(ResponseInterface $psrResponse): ResponseInterface
            {
                return $psrResponse->withBody(stream_for("Hello World!"));
            }
        }
        ]);

        $route = $router->route(new ServerRequest('POST', new Uri("/hello/world")));
        $this->assertEquals("Hello World!", $route->respond(new Response('201'))->getBody()->getContents());


        $route = $router->route(new ServerRequest('GET', new Uri("/hello/world")));
        $this->assertEquals("Hello World!", $route->respond(new Response('201'))->getBody()->getContents());

    }



    public function testAddPath_When_ExistingRoute_Expect_ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $file = tempnam(sys_get_temp_dir(), 'route_');
        file_put_contents($file, '<?php return function(pulledbits\Router\Chain $chain) : void
        {
            $chain->link(function(Psr\Http\Message\ServerRequestInterface $request, \Closure $next) : Psr\Http\Message\ResponseInterface
                {
                    $response = $next($request);
                    return $response->withBody(GuzzleHttp\Psr7\stream_for("Hello World!"));
                });
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

        $router = new Router(["/hello/(?<where>\w+)" => function(Chain $chain) : void
        {
            $chain->link(function(ServerRequestInterface $request, \Closure $next) : ResponseInterface
                {
                    $response = $next($request);
                    return $response->withBody(stream_for('Hello ' . $request->getAttribute('where') . '!'));

            });
        }
        ]);

        $response = $router->route($request);

        $this->assertEquals("Hello wooorld!", $response->respond(new Response('202'))->getBody()->getContents());

    }


    public function testRoute_When_NoMatchingRouteExists_Expect_404ResponseReturned()
    {
        $request = new ServerRequest('GET', new Uri("/hello/world"));

        $router = new Router(["/hello/worl$" => function(Chain $chain): void
            {
                $chain->link(function(ServerRequestInterface $request, \Closure $next) : ResponseInterface
                    {
                        return $next($request);
                    }
                );
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
        $router->addRoute("/hello/world", function(Chain $chain): void {
            $chain->link(function(ServerRequestInterface $request, \Closure $next) : ResponseInterface {
                return $next($request)->withBody(stream_for('Hello'));
            });
        });

        $response = $router->route($request)->respond(new Response('200'));

        $this->assertEquals('200', $response->getStatusCode());
        $this->assertEquals("Hello", $response->getBody()->getContents());
    }
}
