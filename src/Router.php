<?php

class Router
{
    protected $routes = [];
    public function setRoute($route, $controller, $action)
    {
        $this->routes[$route] = ['controller'=>$controller, 'action'=>$action];
    }

    public function run()
    {

    }
}