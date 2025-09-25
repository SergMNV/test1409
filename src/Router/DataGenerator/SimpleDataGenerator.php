<?php

namespace App\Router\DataGenerator;

use App\Router\DataGenerator;
use App\Router\Route;
use InvalidArgumentException;

class SimpleDataGenerator implements DataGenerator
{
    private array $supportedMethods = [
        'GET',
        'POST',
        'DELETE',
        'PUT',
    ];

    private array $routes = [];

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $method = strtoupper($method);

        if (!in_array($method, $this->supportedMethods)) {
            throw new InvalidArgumentException();
        }

        $path = $this->normalisePath($path);

        $route = $this->routes[] = new Route($method, $path, $handler);
        
        return $route;
    }

    public function getData(): array
    {
        return $this->routes;
    }

    public function normalisePath(string $path): string
    {
        $path = rtrim(strtolower($path), '/');
        $path = preg_replace(
            '#[/]{2,}#',
            '/',
            $path,
        );
        return $path;
    }
}
