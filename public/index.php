<?php
/**
 * чтобы работала функция Router:redirect
 */
ob_start();
?>

<a href="/">forward page</a>
<br>
<a href="/home/admin">home/{user}</a>
<br>
<a href="/products/2">products/{page?}</a>
<br>
<a href="/redirect">redirect</a>
<br>

<?php

use App\Http\Request;
use App\Router\DataGenerator\SimpleDataGenerator;
use App\Router\Dispatcher\SimpleDispatcher;
use App\Router\Router;

require_once __DIR__ . '../../vendor/autoload.php';
$routes = require_once __DIR__ . '../../routes/routes.php';

$router = new Router(
    new SimpleDataGenerator(),
    new SimpleDispatcher(),
);

$routes($router);

$request = Request::setGlobals();

$matching = $router->dispatch(
    $request->server['REQUEST_METHOD'],
    $request->server['REQUEST_URI'],
);
/**
 * $matching = [
 *     0 => int $status,
 *     1 => mixed $handler,
 *     2 => array $vars,
 *  ]
 */
switch ($matching[0]) {
    case App\Router\Dispatcher::NOT_FOUND:
        print 'error 404';
        break;

    case App\Router\Dispatcher::METHOD_NOT_ALLOWED:
        print  'error  400';
        break;

    case \App\Router\Dispatcher::FOUND:
        $handler = $matching[1];
        $vars = $matching[2];

        print call_user_func($handler);

        dump($vars);

        break;
}

exit;
