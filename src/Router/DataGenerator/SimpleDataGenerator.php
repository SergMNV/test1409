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
    private array $staticRoutes = [];
    /**
     * роуты с параметрами
     * массив содержит массивы с 2мя ключами:
     *    [0] => [
     *      [0] => Route,
     *      [1] => [path, pattern, vars]
     *    ]
     */
    private array $variableRoutes = [];

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        // добавление роута с параметрами
        if (strpbrk($path, '{}')) {
            //$this->parse($path)['pattern']
            $route = new Route($method, $path, $handler);
            $this->variableRoutes[] = [$route, $this->parse($path)];
            /**
             * $parse = [
             *          'pattern' => "/home/([^/]+)/",
             *          'vars' => [ 0 => 'user', ...]
             *     ]
             */
            return $route;
        }

        // добавление статического роута
        $route = $this->staticRoutes[] = new Route($method, $path, $handler);

        return $route;
    }

    public function getData(): array
    {
        return [
            'static' => $this->staticRoutes,
            'variable' => $this->variableRoutes
        ];
    }

    private function parse(string $path): array
    {
        $vars = [];

        $pattern = preg_replace_callback(
            '#/{([^}]+)}#',
            function ($matches) use (&$vars) {
                /**
                 *  0 => "/{user}"
                 * 1 => "user"
                 */

                if (str_contains($matches[1], '?')) {
                    return '/([^/]+)';
                }

                array_push($vars, $matches[1]);

                return '/([^/]+)';
            },
            $path,
        );

        // dd($pattern);

        return [
            'pattern' => $pattern,
            // 'params' => [varName => value,...]
            'vars' => $vars,
        ];
    }
}
