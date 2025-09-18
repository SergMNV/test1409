<?php

namespace App\Router;

interface DataGenerator
{
    public function getData(): Collection;
    
    public function addRoute(): Route;
}
