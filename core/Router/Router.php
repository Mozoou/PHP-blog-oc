<?php

namespace Core\Router;

class Router
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    public array $handlers = [];

    private static ?self $_instance = null;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Router();
        }
        return self::$_instance;
    }

    public function get(string $path, string $controller, string $controllerMethod, ?array $params = null): void
    {
        $this->addHandler($path, self::METHOD_GET, $controller, $controllerMethod, $params);
    }

    public function post(string $path, string $controller, string $controllerMethod, ?array $params = null): void
    {
        $this->addHandler($path, self::METHOD_POST, $controller, $controllerMethod, $params);
    }

    public function run(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $requestUri['path'];
        $callback = null;
        $params = null;

        foreach ($this->handlers as $handler) {
            if (
                $requestPath === $handler['path']
                && $requestMethod === $handler['method']
            ) {
                $controller = new $handler['controller'];
                $callback = [$controller, $handler['controllerMethod']];
            }
        }

        if (!$callback) {
            return;
        }

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }

    public function redirectToRoute(string $route): void
    {
        // check if route exist
        foreach ($this->handlers as $handler) {
            if (
                $route === $handler['path']
                && self::METHOD_GET === $handler['method']
            ) {
                $controller = new $handler['controller'];
                $callback = [$controller, $handler['controllerMethod']];
                call_user_func_array($callback, [
                    array_merge($_GET, $_POST)
                ]);
            }
        }
    }

    private function addHandler(string $path, string $method, string $controller, string $controllerMethod, ?array $params = null): void
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'params' => $params,
        ];
    }
}

