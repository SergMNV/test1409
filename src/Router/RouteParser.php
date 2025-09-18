<?php

namespace App\Router;

interface RouteParser
{
    public function parse(Route $route): Route;
}
