<?php

use App\Router\Router;

return function (Router $router) {
    $router->addRoute('get', '/', fn() => 'hello world!');
};
