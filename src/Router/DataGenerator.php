<?php

namespace App\Router;

interface DataGenerator
{
    public function addRoute(string $method, string $path, mixed $handler): Route;

    public function getData(): array;
}
