<?php

namespace App\Router;

final class Router
{
    private array $errorHandlers = [];

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

        dump($routes);

        return $this->dispatcher->dispatch($requestMethod, $requestUri, $routes);
    }

    public function redirect(string $path): void
    {
        $path = $this->normalisePath($path);
        header("Location: {$path}");
        exit;
    }

    private function normalisePath(string $path): string
    {
        $path = trim(strtolower($path), '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path,);

        return $path;
    }

    public function setErrorHandler(int $code, mixed $handler): void
    {
        $this->errorHandlers[$code] = $handler;
    }

    public function dispatchNotFound(): callable
    {
        $error = $this->errorHandlers[404] ??= fn() => 'dispatch not found 404';
        return $error;
    }

    public function dispatchNotAllowed(): callable
    {
        $error = $this->errorHandlers[400] ??= fn() => 'method not allowed 400';
        return $error;
    }

    public function dispatchServerError(): callable
    {
        $error = $this->errorHandlers[500] ??= fn() => 'server error 500';
        return $error;
    }
}
