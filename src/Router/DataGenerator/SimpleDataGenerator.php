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

    private array $staticRoutes = [];
    private array $variableRoutes = [];

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $method = strtoupper($method);

        if (!in_array($method, $this->supportedMethods)) {
            throw new InvalidArgumentException();
        }

        if (strpbrk($path, '{}')) {
            $this->variableRoutes[] = $route = new Route($method, $path, $handler);

            return $route;
        }

        $route = $this->staticRoutes[] = new Route($method, $path, $handler);

        return $route;
    }

    public function getData(): array
    {
        return [
            'static' => $this->staticRoutes,
            'variable' => $this->variableRoutes
        ];
    }

    private function parse(Route $route) {}
}
