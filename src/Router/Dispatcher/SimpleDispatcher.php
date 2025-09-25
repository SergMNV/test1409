<?php

namespace App\Router\Dispatcher;

use App\Router\Dispatcher;
use App\Router\Route;
use InvalidArgumentException;

class SimpleDispatcher implements Dispatcher
{
    public function dispatch(string $method, string $uri, array $data): array
    {
        foreach ($data as $route) {
            if (!$route instanceof Route) {
                throw new InvalidArgumentException();
            }

            if (
                $route->method === $method &&
                $route->path === $path
            ) {
                return $route;
            }
        }

        return [];
    }
}
