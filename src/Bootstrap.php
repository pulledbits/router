<?php
/**
 * User: hameijer
 * Date: 16-3-17
 * Time: 15:05
 */

namespace rikmeijer\Teach;

interface Bootstrap
{
    public function router(array $routes) : \rikmeijer\Teach\Router;

    public function request() : \Psr\Http\Message\ServerRequestInterface;

    public function response(int $status, string $body) : \Psr\Http\Message\ResponseInterface;

    public function resources() : \rikmeijer\Teach\Resources;

}