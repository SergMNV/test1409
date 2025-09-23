<?php

namespace App\Router;

interface DataCollection
{
    public function createCollection(array $routes): Collection;
}
