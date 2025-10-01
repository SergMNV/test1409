<?php

use App\Router\Router;

return function (Router $router) {
    $router->addRoute('GET', '/', fn() => 'forward page');
    $router->addRoute(
        'GET',
        '/home/{user}',
        fn() => 'home page'
        // function () use ($router) {
        //     print 'home page';
        //     dd($router->current()->parameters());
        // }
    )->name('home');
    $router->addRoute('GET', '/registration', fn() => 'registration page');
    $router->addRoute('GET', '/product/{id?}', fn() => 'registration page');
};
