<?php

namespace App\Router\SimpleRouter;

use App\Router\Route;
use App\Router\Router;

class SimpleRouter implements Router
{
    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $route = new Route($method, $path, $handler);
        return $route;
    }

    public function dispatch(string $method, string $uri): array
    {
        return [];
    }
}
