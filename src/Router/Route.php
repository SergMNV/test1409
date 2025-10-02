<?php

namespace App\Router;

final class Route
{
    private ?string $name = null;

    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly  mixed $handler,
    ) {}

    public function name(string $name): ?string
    {
        if (!empty($name)) {
            $this->name = $name;
        }

        return $this->name;
    }
}
