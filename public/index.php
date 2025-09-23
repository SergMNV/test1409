<?php

use App\Http\Request;
use App\Router\SimpleRouter\SimpleRouter;

require_once __DIR__ . '../../vendor/autoload.php';

$request = Request::setGlobals();

//dd($request->server);

$routes = require_once __DIR__ . '../../routes/routes.php';
$router = new SimpleRouter();

$routes($router);

dd($router->dispatch(
    $request->server['REQUEST_METHOD'],
    $request->server['REQUEST_URI'],
));
