<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller, $action = 'index', $middleware = [])
    {
        // Convert dynamic placeholders (e.g., {id}) to regex patterns
        $uriPattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $uri);
        $uriPattern = "#^" . $uriPattern . "$#";

        $this->routes[] = [
            'uri' => $uri,
            'pattern' => $uriPattern,
            'controller' => $controller,
            'action' => $action,
            'method' => $method,
            'middleware' => $middleware
        ];

        return $this;
    }

    public function get($uri, $controller, $action = 'index', $middleware = [])
    {
        return $this->add('GET', $uri, $controller, $action, $middleware);
    }

    public function post($uri, $controller, $action = 'index', $middleware = [])
    {
        return $this->add('POST', $uri, $controller, $action, $middleware);
    }

    public function patch($uri, $controller, $action = 'index', $middleware = [])
    {
        return $this->add('PATCH', $uri, $controller, $action, $middleware);
    }

    public function delete($uri, $controller, $action = 'index', $middleware = [])
    {
        return $this->add('DELETE', $uri, $controller, $action, $middleware);
    }

    public function only($middleware)
    {
        $index = array_key_last($this->routes);
        $this->routes[$index]['middleware'][] = $middleware;

        return $this;
    }
    

    public function middleware(...$middlewares)
    {
        $index = array_key_last($this->routes);

        // Append the middlewares to the route
        $this->routes[$index]['middleware'] = array_merge(
            $this->routes[$index]['middleware'] ?? [],
            $middlewares
        );

        return $this;
    }


    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            // Match the URI against the pattern
            if ($route['method'] === strtoupper($method) && preg_match($route['pattern'], $uri, $matches)) {
                // Extract dynamic parameters from URI
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Handle middleware
                $this->handleMiddleware($route['middleware']);

                // Resolve the controller
                return $this->resolveController($route['controller'], $route['action'], $params);
            }
        }

        $this->abort();
    }

    protected function handleMiddleware($middlewares)
    {
        Middleware::resolve($middlewares);
    }

    protected function resolveController($controller, $action, $params = [])
    {
        // Check if the controller is a legacy file-based controller
        $controllerPath = base_path("Http/Controllers/{$controller}");
        if (file_exists($controllerPath)) {
            return require $controllerPath;
        }

        // Assume the controller is a class, potentially in a nested namespace
        $controllerClass = "Http\\Controllers\\" . str_replace('/', '\\', $controller);

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found.");
        }

        // Instantiate the class-based controller
        $instance = new $controllerClass();

        if (!method_exists($instance, $action)) {
            throw new \Exception("Method {$action} not found in {$controllerClass}.");
        }

        // Call the action method, passing dynamic parameters
        return $instance->$action(...array_values($params));
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function abort($code = 404)
    {
        http_response_code($code);
        require base_path("views/{$code}.php");
        die();
    }
}
