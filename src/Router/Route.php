<?php

namespace App\Router;

final class Route
{
    private string $name;

    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly  mixed $handler,
    ) {}

    public function name(string $name): Route
    {
        $this->name = $name;
        return $this;
    }

}
