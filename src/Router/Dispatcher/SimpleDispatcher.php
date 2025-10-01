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
         *$routes = [
         * 'static' => $this->staticRoutes,
         * 'variable' => $this->variableRoutes
         * ]
         */


        foreach ($routes['static'] as $route) {
            // проверка обьекта 
            if (!$route instanceof Route) {
                throw new InvalidArgumentException();
            }

            if (
                $route->method === $requestMethod &&
                $route->path === $requestUri
            ) {
                // $this->current = $route;

                return [self::FOUND, $route->handler, $this->vars ?? null];
            }
        }

        foreach ($routes['variable'] as $item) {
            // Route
            $route = $item[0];
            // parse = 
            //  * [
            //  *  'path' => "/home/{user}/",
            //  *  'pattern' => "/home/([^/]+)/",
            //  *  'vars' => [
            //  *        0 => "user"
            //  *   ]
            //  * ]
            $parse = $item[1];

            // dd("{$parse['pattern']}");

            if (preg_match("#{$parse['pattern']}#", $requestUri, $matches)) {
                /**
                 *  $matches = array [
                 *     0 => "/home/admin/"
                 *     1 => "admin"
                 *    ]
                 */
                dump($matches);

                $vars = [];
                // foreach ($parse['vars'] as $key => $nameVar) {
                //     $vars[$nameVar] = $matches[$key];
                // }
                foreach ($parse['vars'] as $key => $nameVar) {
                    $vars[$nameVar] = array_shift($matches);
                }

                // $this->current = $route;
                // $this->current()->parameters($vars);

                return [self::FOUND, $route->handler, $vars ?? null];
            } #



            /**
             * $item = [Route $route,
             * array $parse = 
             * [
             *  'path' => "/home/{user}/",
             *  'pattern' => "/home/([^/]+)/",
             *  'vars' => [
             *        0 => "user"
             *   ]
             *]]
             */
        }



        return [self::NOT_FOUND, null, null];
    }

    // public function current(): ?Route
    // {
    //     return $this->current;
    // }
}
