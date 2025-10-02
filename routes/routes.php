<?php

use App\Router\Router;

return function (Router $router) {
    $router->addRoute('GET', '/', fn() => 'forward page');

    $router->addRoute(
        'GET',
        '/home/{user}',
        fn() => 'home page'
    )->name('home');
    //$r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    $router->addRoute('GET', '/registration', fn() => 'registration page');
    $router->addRoute('GET', '/product/{id?}', fn() => 'registration page');

    $router->addRoute('GET', '/redirect', function () use ($router) {
        return $router->redirect('/home/redirect');
    });
};
