<?php

use App\Router\Router;

return function (Router $router) {
    $router->addRoute('GET', '/', fn() => 'forward page');
    $router->addRoute('GET', '/home/{user}', fn() => 'home page')->name('home');
    $router->addRoute('GET', '/registration', fn() => 'registration page');
};
