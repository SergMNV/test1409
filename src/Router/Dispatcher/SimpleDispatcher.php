<?php

namespace App\Router\Dispatcher;

use App\Router\Dispatcher;
use App\Router\Route;
use InvalidArgumentException;

class SimpleDispatcher implements Dispatcher
{
    // private array $routes;
    // private ?Route $current = null;
    private array $vars;

    public function dispatch(string $requestMethod, string $requestUri, array $routes): array
    {
        /**
         *  $routes = ['static' => $this->staticRoutes,
         *             'variable' => $this->variableRoutes]
         */
        foreach ($routes['static'] as $route) {
            if (
                $route->method === $requestMethod &&
                $route->path === $requestUri
            ) {

                return [self::FOUND, $route->handler, $this->vars ?? null];
            }
        }

        foreach ($routes['variable'] as $item) {
            /**
             * $item = [
             *      [0] => Route,
             *      [1] => [
             *          'pattern' => "/home/([^/]+)/",
             *          'vars' => [
             *              0 => "user",
             *          ]
             *      ]
             */
            $route = $item[0];
            $parse = $item[1];
            /**
             * $parameterNames =  [ 0 => 'user', ...]
             */
            $parameterNames = [];
            $parameterValues = [];

            foreach ($parse['vars'] as $name) {
                array_push($parameterNames, $name);
            }

            if (preg_match("#{$parse['pattern']}#", $requestUri, $matches)) {
                /**
                 *  $matches = array [
                 *      0 => "/home/admin/"
                 *      1 => "admin"
                 *    ]
                 */

                array_shift($matches);
                $parameterValues = $matches;

                $vars = array_combine($parameterNames, $parameterValues);

                return [self::FOUND, $route->handler, $vars ?? null];
            }


            /**
             * $item = [Route $route,
             * array $parse = 
             *      [
             *          'pattern' => "/home/([^/]+)/",
             *          'vars' => [0 => "user", ...]
             *      ]
             */
        }

        return [self::NOT_FOUND, null, null];
    }
}
