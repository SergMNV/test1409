<?php

namespace App\Router;

use Exception;

final class Router
{
    private Route $current;

    public function __construct(
        private DataGenerator $dataGenerator,
        private Dispatcher $dispatcher,
    ) {}

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $path = $this->normalisePath($path);
        $route = $this->dataGenerator->addRoute($method, $path, $handler);

        return $route;
    }

    public function dispatch(string $requestMethod, string $requestUri): array
    {
        $routes = $this->dataGenerator->getData();
        $requestUri = $this->normalisePath($requestUri);

        // test
        dd($routes);

        return $this->dispatcher->dispatch($requestMethod, $requestUri, $routes);
    }

    public function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    public function current(): Route
    {
        $current = $this->current;
        if (!$current) {
            return throw new Exception('current route error');
        }

        return $this->current;
    }

    private function normalisePath(string $path): string
    {
        $path = rtrim(strtolower($path));
        $path = preg_replace(
            '#[/]{2,}#',
            '/',
            $path,
        );

        return $path;
    }
}
