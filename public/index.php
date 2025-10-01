<?php

use App\Http\Request;
use App\Router\DataGenerator\SimpleDataGenerator;
use App\Router\Dispatcher\SimpleDispatcher;
use App\Router\Router;

require_once __DIR__ . '../../vendor/autoload.php';

$request = Request::setGlobals();

//dd($request->server);

$routes = require_once __DIR__ . '../../routes/routes.php';
$router = new Router(
    new SimpleDataGenerator(),
    new SimpleDispatcher(),
);

$routes($router);

$matching = $router->dispatch(
    $request->server['REQUEST_METHOD'],
    $request->server['REQUEST_URI'],
);
/**
 * matching = [
 *      int $status,
 *      mixed $handler,
 *      array $vars
 * ]
 */

switch ($matching[0]) {
    case App\Router\Dispatcher::NOT_FOUND:
        print  call_user_func($router->dispatchNotFound());
        break;

    case App\Router\Dispatcher::METHOD_NOT_ALLOWED:
        print  call_user_func($router->dispatchNotAllowed());
        break;

    case \App\Router\Dispatcher::FOUND:
        $handler = $matching[1];
        $vars = $matching[2];

        print call_user_func($handler);
        break;
}

exit;
