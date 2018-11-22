<?php


namespace pulledbits\Router;


abstract class RouteEndPointDecorator
{

    protected $wrappedEndPoint;

    public function __construct(RouteEndPoint $wrappedEndPoint)
    {
        $this->wrappedEndPoint = $wrappedEndPoint;
    }

}