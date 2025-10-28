<?php
namespace App\Utils;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = strtok($_SERVER['REQUEST_URI'], '?');

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                [$controller, $method] = $route['handler'];
                $instance = new $controller();
                return call_user_func([$instance, $method]);
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
