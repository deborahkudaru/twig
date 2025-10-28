<?php
namespace App\Utils;

use Twig\Environment;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = strtok($_SERVER['REQUEST_URI'], '?');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '([^/]+)', $route['path']);

            if (preg_match('#^' . $pattern . '$#', $requestUri, $matches)) {
                array_shift($matches);

                [$controller, $method] = $route['handler'];

                $twig = $GLOBALS['twig'] ?? null;
                $instance = $twig ? new $controller($twig) : new $controller();

                return call_user_func_array([$instance, $method], $matches);
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
