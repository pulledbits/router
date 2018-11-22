<?php
namespace pulledbits\Router;

abstract class RouteEndPointDecorator implements RouteEndPoint
{

    protected $wrappedEndPoint;

    public function __construct(RouteEndPoint $wrappedEndPoint)
    {
        $this->wrappedEndPoint = $wrappedEndPoint;
    }
}