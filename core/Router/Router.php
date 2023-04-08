<?php

namespace Core\Router;

use Symfony\Component\HttpFoundation\Request;

class Router
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    private ?Request $request = null;  
    public array $handlers = [];

    private static ?self $_instance = null;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }


    /**
     * Retreive instance of Router (singelton)
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new Router();
        }
        return self::$_instance;
    }

    /**
     * Add a get route
     * @param string $path
     * @param string $controller
     * @param string $controllerMethod
     * @return void
     */
    public function get(string $path, string $controller, string $controllerMethod): void
    {
        $this->addHandler($path, self::METHOD_GET, $controller, $controllerMethod);
    }

    /**
     * Add a post route
     * @param string $path
     * @param string $controller
     * @param string $controllerMethod
     * @return void
     */
    public function post(string $path, string $controller, string $controllerMethod): void
    {
        $this->addHandler($path, self::METHOD_POST, $controller, $controllerMethod);
    }

    /**
     * Run controllerMethod route called
     * @return void
     */
    public function run(): callable | null
    {
        $requestPath = $this->request->getPathInfo();
        $requestMethod = $this->request->getMethod();
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
            return null;
        }

        return $callback();
    }

    /**
     * Add a route to handlers array
     * @param string $path
     * @param string $method
     * @param string $controller
     * @param string $controllerMethod
     * @return void
     */
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