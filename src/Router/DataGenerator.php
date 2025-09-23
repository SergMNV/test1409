<?php

namespace App\Router;

interface DataGenerator
{
    public function getCollection(): Collection;
    
    public function addRoute(): Route;
}
