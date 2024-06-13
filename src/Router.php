<?php
namespace App;

class Router{
    protected array $routes = [];
    public function setRoute($route, $controller, $action):void{
        //REGEX for inpath parameters
        $route = preg_replace('/{([^}]+)}/', '(?P<$1>[^/]+)', $route);
        $route = '#^' . $route . '$#';
        $this->routes[$route] = ['controller'=>$controller, 'action'=>$action];
    }

    public function run():void{
        $uri = strtok($_SERVER['REQUEST_URI'], '?') ;
        foreach ($this->routes as $route => $data) {
            if (preg_match($route, $uri, $matches)) {
                $controller = new $data['controller']();
                $action = $data['action'];
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $controller->$action($params);
                return;
            }
        }

        Errors::NotFound();
    }
}