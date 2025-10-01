<?php

namespace App\Router\DataGenerator;

use App\Router\DataGenerator;
use App\Router\Route;
use InvalidArgumentException;

class SimpleDataGenerator implements DataGenerator
{
    private array $supportedMethods = [
        'GET',
        'POST',
        'DELETE',
        'PUT',
    ];

    private array $staticRoutes = [];
    private array $variableRoutes = [];

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $method = strtoupper($method);

        if (!in_array($method, $this->supportedMethods)) {
            throw new InvalidArgumentException();
        }

        if (strpbrk($path, '{}')) {
            //$this->parse($path)['pattern']
            $route = new Route($method, $path, $handler);
            $parse = $this->parse($path);

            $this->variableRoutes[] = [
                // new Route($method, $path, $handler),
                $route,
                $parse,
            ];

            return $route;
        }

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
        // $path = $path;
        // dd($path);
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

        // preg_replace_callback(
        //     '#/{([^}]+)}#',
        //     function ($matches) {
        //         return $matches;
        //     },
        //     $path,
        // );

        // dd($pattern);

        return [
            'path' => $path,
            'pattern' => $pattern,
            'vars' => $vars,
        ];
    }
}
