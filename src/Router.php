<?php
namespace App;

class Router{
    protected array $routes = [];
    public function setRoute($route, $controller, $action):void{
        $this->routes[$route] = ['controller'=>$controller, 'action'=>$action];
    }

    public function run():void{
        $uri = strtok($_SERVER['REQUEST_URI'], '?') ;
        if(!array_key_exists($uri, $this->routes)) {
            Errors::NotFound();
        }
        $controller = new $this->routes[$uri]['controller']() ;
        $action =  $this->routes[$uri]['action'];
        $controller->$action();
    }
}