<?php
/**
 * User: hameijer
 * Date: 16-3-17
 * Time: 13:40
 */

namespace rikmeijer\Teach;


class Request
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;


    /**
     * @var Response
     */
    private $response;

    public function __construct(\Psr\Http\Message\ServerRequestInterface $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function respond(int $status, string $body) : void {
        $this->response->send($status, $body);
    }

    public function respondWithHeaders(int $status, array $headers, string $body) : void {
        $this->response->sendWithHeaders($status, $headers, $body);
    }

    public function __call(string $method, array $arguments)
    {
        return $this->request->$method(...$arguments);
    }

    public function handle($route, Resources $resources)
    {
        if ($route === false) {
            $this->respond(404, 'Failure');
        } else {
            \Closure::bind($route->handler, $this, __CLASS__);
            call_user_func($route->handler, $resources, $this);
        }
    }
}