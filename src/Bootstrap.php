<?php
/**
 * User: hameijer
 * Date: 16-3-17
 * Time: 15:05
 */

namespace rikmeijer\Teach;

interface Bootstrap
{
    public function route(\Psr\Http\Message\ServerRequestInterface $request) : \Aura\Router\Route;

    public function request() : \Psr\Http\Message\ServerRequestInterface;

    public function responseFactory() : callable;

    public function resources() : \rikmeijer\Teach\Resources;
}