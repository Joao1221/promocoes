<?php
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->map('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->map('POST', $path, $handler);
    }

    private function map(string $method, string $path, array $handler): void
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = '/' . trim($uri, '/');
        $uri = $uri === '/' ? '/' : rtrim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = $pattern === '/' ? '#^/$#' : '#^' . rtrim($pattern, '/') . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                [$controllerName, $action] = $route['handler'];
                $controller = new $controllerName();
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        http_response_code(404);
        echo 'Pagina nao encontrada.';
    }
}
