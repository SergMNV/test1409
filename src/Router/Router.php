<?php

namespace App\Router;

interface Router
{
    public function addRoute(string $method, string $path, mixed $handler): Route;

    public function dispatch(string $method, string $uri): array;
}
