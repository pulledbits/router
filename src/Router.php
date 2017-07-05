<?php

namespace pulledbits\Router;

use GuzzleHttp\Psr7\Response;

class Router
{
    /**
     * @var Handler[]
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(\Psr\Http\Message\ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        /**
         * @var $matcher Matcher
         */
        foreach ($this->routes as $routeRegularExpression => $matcher) {
            if ($matcher->matchRequest($request)) {
                return $matcher->makeHandler($request)->makeResponse();
            }
        }
        return new Response(404);
    }

}