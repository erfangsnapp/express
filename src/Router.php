<?php
namespace App;
use App\Errors;
class Router{
    protected array $routes = [];
    public function setRoute($route, $controller, $method, $action):void{
        //REGEX for in-path parameters
        $regex_placeholder_route = preg_replace('/{([^}]+)}/', '(?P<$1>[^/]+)', $route);
        $regex_route_to_save = '#^' . $regex_placeholder_route . '$#';
        $this->routes[] = [
            'router_url'=> $regex_route_to_save,
            'controller'=>$controller,
            'method'=>$method,
            'action'=>$action
        ];
    }

    public function run():void{
        $uri = strtok($_SERVER['REQUEST_URI'], '?') ;

        $found_but_Method_not_matched = false;

        foreach ($this->routes as $route) {
            
            $route_url = $route['router_url'];
            $controller = $route['controller'];
            $method = $route['method'];
            $action = $route['action'];

            if (preg_match($route_url, $uri, $matches)) {
                if ($_SERVER['REQUEST_METHOD'] != $method){
                    $found_but_Method_not_matched = true;
                    continue;
                }
                $controller = new $controller();
                // Extract named parameters from matches
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                try{
                    $controller->$action($params);
                }
                catch (\Throwable $e){
                    Errors::ServerError();
                }
                return;
            }
        }

        if ($found_but_Method_not_matched){
            Errors::MethodNotAllowed();
        }

        Errors::NotFound();
    }
}