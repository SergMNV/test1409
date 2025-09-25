<?php

namespace App\Router;

final class Router
{
    public function __construct(
        private DataGenerator $dataGenerator,
        private Dispatcher $dispatcher,
    ) {}

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $route = $this->dataGenerator->addRoute($method, $path, $handler);
        return $route;
    }

    public function dispatch(string $method, string $uri): array
    {
        $data = $this->dataGenerator->getData();
        return $this->dispatcher->dispatch($method, $uri, $data);
    }
}
