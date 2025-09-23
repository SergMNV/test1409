<?php

namespace App\Router\SimpleRouter;

use App\Router\Route;
use App\Router\Router;

class SimpleRouter implements Router
{

    private array $routes = [];

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $route = $this->routes[] = new Route($method, $path, $handler);
        return $route;
    }

    public function dispatch(string $method, string $uri): array
    {
        /**
         * array [status handler args]
         */
        foreach ($this->routes as $route) {
            if (
                $route->method === $method &&
                $route->path === $uri
            ) {
                return [
                    'FOUND',
                    $route->handler,
                    []
                ];
            }
        }

        return [];
    }
}
