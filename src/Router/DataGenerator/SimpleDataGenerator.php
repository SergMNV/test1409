<?php

namespace App\Router\DataGenerator;

use App\Router\DataGenerator;
use App\Router\Route;

class SimpleDataGenerator implements DataGenerator
{
    /**
     * статические роуты без параметров строки
     * [0] => Route, ...
     */
    private array $staticRouteArray = [];
    /**
     * роуты с параметрами
     * массив содержит массивы с 2мя ключами:
     *    [0] => [
     *      [0] => Route,
     *      [1] => [path, pattern, vars]
     *    ], ...
     */
    private array $variableRouteData = [];

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        // добавление роута с параметрами
        if (strpbrk($path, '{}')) {
            //$this->parse($path)['pattern']
            $route = new Route($method, $path, $handler);
            $this->variableRouteData[] = [$route, $this->parse($path)];
            /**
             * $parse = [
             *          'pattern' => "/home/([^/]+)/",
             *          'vars' => [ 0 => 'user', ...]
             *     ]
             */
            return $route;
        }
        // добавление статического роута
        $route = $this->staticRouteArray[] = new Route($method, $path, $handler);

        return $route;
    }

    public function getData(): array
    {
        return [
            'static' => $this->staticRouteArray,
            'variable' => $this->variableRouteData
        ];
    }

    private function parse(string $path): array
    {
        $vars = [];

        $pattern = preg_replace_callback(
            // здесь важен символ / в конце строки
            '#{([^}]+)}/#',
            function ($matches) use (&$vars) {
                /** 
                 * $matches = [
                 *      0 => "/{user}"
                 *      1 => "user"
                 *  ]
                 */
                if (str_contains($matches[1], '?')) {
                    // если параметр является необязательным
                    array_push($vars, rtrim($matches[1], '?'));
                    return '([^/]*)(?:/?)';
                }
                // если параметр является обязательным
                array_push($vars, $matches[1]);
                return '([^/]+)/';
            },
            $path,
        );
        // return [
        //     "pattern" => "/product/([^/]*)(?:/?)"
        //     "vars" =>  [0 => "id"]
        //  ]
        return [
            'pattern' => $pattern,
            'vars' => $vars,
        ];
    }
}
