<?php

namespace App\Router\Dispatcher;

use App\Router\Dispatcher;
use App\Router\Route;
use InvalidArgumentException;

class SimpleDispatcher implements Dispatcher
{
    private array $routes;
    private array $vars;
    
    public function dispatch(string $requestMethod, string $requestUri, array $routes): array
    {
        foreach ($routes as $route) {
            if (!$route instanceof Route) {
                throw new InvalidArgumentException();
            }

            if (
                $route->method === $requestMethod &&
                $route->path === $requestUri
            ) {
                return [self::FOUND, $route, $this->vars ?? null];
            }
        }

        return [self::NOT_FOUND, null, null];
    }
}
