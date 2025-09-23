<?php

use App\Router\Router;

return function (Router $router) {
    $router->addRoute('GET', '/', fn() => 'hello world!');
};
