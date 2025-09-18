<?php

namespace App\Router;

class Route
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly  mixed $handler,
    ) {}
}
