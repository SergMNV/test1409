<?php

namespace App\Router;

interface Dispatcher
{
    public function dispatch(string $method, string $uri, array $data): array;
}
