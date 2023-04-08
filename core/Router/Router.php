<?php

namespace Core\Router;

use Ghostwriter\Environment\Environment;

class Router
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    public array $handlers = [];

    private static ?self $_instance = null;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new Router();
        }
        return self::$_instance;
    }

    public function get(string $path, string $controller, string $controllerMethod): void
    {
        $this->addHandler($path, self::METHOD_GET, $controller, $controllerMethod);
    }

    public function post(string $path, string $controller, string $controllerMethod): void
    {
        $this->addHandler($path, self::METHOD_POST, $controller, $controllerMethod);
    }

    public function run(): void
    {
        //[x]TODO: Direct use of $_SERVER Superglobal detected.        
        $environment = new Environment();
        $requestUri = parse_url($environment->getServerVariables()['REQUEST_URI']);
        $requestMethod = $environment->getServerVariables()['REQUEST_METHOD'];
        $requestPath = $requestUri['path'];
        $callback = null;

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

        call_user_func_array($callback, []);
    }

    private function addHandler(string $path, string $method, string $controller, string $controllerMethod): void
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
        ];
    }
}
