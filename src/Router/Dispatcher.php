<?php

namespace App\Router;

use const Dom\NOT_FOUND_ERR;

interface Dispatcher
{
    const NOT_FOUND = 0;
    const FOUND = 1;
    const METHOD_NOT_ALLOWED = 2;

    public function dispatch(string $requestMethod, string $requestUri, array $routes): array;
}
