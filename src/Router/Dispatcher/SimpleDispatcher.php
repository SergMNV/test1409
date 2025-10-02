<?php

namespace App\Router\Dispatcher;

use App\Router\Dispatcher;

class SimpleDispatcher implements Dispatcher
{
    private array $vars;

    public function dispatch(string $requestMethod, string $requestUri, array $routes): array
    {
        /**
         *  $routes = ['static' => $this->staticRoutes,
         *             'variable' => $this->variableRoutes]
         */
        dump($routes);

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
                $parameterValues = array_merge(
                    $parameterValues,
                    array_fill(
                        count($parameterNames) - count($parameterValues),
                        count($parameterNames) - 1,
                        null
                    )
                );

                $this->vars = array_combine($parameterNames, $parameterValues);

                return [self::FOUND, $route->handler, $this->vars ?? null];
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
