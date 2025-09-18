<?php

namespace App\Router;

use App\Http\Request;

interface Dispatcher
{
    // public abstract function dispatch(Request,Collection): array;
    public function dispatch(Request $request, Collection $collection): array;
}
