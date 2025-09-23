<?php

namespace App\Router;

final class Route
{
    private ?string $name = null;
    // private array $middlewares = [];

    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly  mixed $handler,
    ) {}

    public function name(?string $name = null)
    {
        if ($name) {
            $this->name = $name;
            return $this;
        }

        return $this->name;
    }

    // public function middleware(string $alias, mixed $handler)
    // {
    //     $this->middlewares[$alias] = $handler;
    //     return $this;
    // }
}
