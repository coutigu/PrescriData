<?php
namespace Core;

class Router {
    private $routes = [];

    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($url, $method) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['path']);
                $pattern = "@^" . $pattern . "$@D";
                
                if (preg_match($pattern, $url, $matches)) {
                    array_shift($matches);
                    
                    $controllerName = "\\Controllers\\" . $route['controller'];
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        call_user_func_array([$controller, $route['action']], $matches);
                        return true;
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
        return false;
    }
}
