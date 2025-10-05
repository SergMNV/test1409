<?php

namespace App\Router\Dispatcher;

use App\Router\Dispatcher;
use App\Router\Route;

class SimpleDispatcher implements Dispatcher
{
    public function dispatch(string $requestMethod, string $requestUri, array $routes): array
    {
        /**
         *  $routes = [
         *      'static' => [],
         *      'variable' => [],
         *      ];
         */
        [$staticRouteArray, $variableRouteData] = [$routes['static'], $routes['variable']];

        foreach ($staticRouteArray as $route) {
            $matching = $this->dispatchStaticRoute($requestMethod, $requestUri, $route);
            if ($matching) {
                //  FIX ME стоит ли тут делать еще что то
                return $matching;
            }
        }

        foreach ($variableRouteData as $routeParseArrayItem) {
            $matching = $this->dispatchVariableRoute($requestMethod, $requestUri, $routeParseArrayItem);
            if ($matching) {
                return $matching;
            }
        }

        return [self::NOT_FOUND, null, null];
    }

    private function dispatchStaticRoute(string $requestMethod, string $requestUri, Route $route): array|false
    {
        if (
            $route->method === $requestMethod &&
            $route->path === $requestUri
        ) {
            return [self::FOUND, $route->handler, null];
        }
        // проверка наличия маршрута
        if ($route->path === $requestUri) {
            return [self::METHOD_NOT_ALLOWED, null, null];
        }

        return false;
    }

    private function dispatchVariableRoute(string $requestMethod, string $requestUri, array $routeParseArrayItem)
    {
        /**
         * $routeParseArrayItem = [
         *      [0] => Route,
         *      [1] => [
         *          'pattern' => "/home/([^/]+)/",
         *          'vars' => [
         *              0 => "user",
         *          ]
         *      ]
         */
        [$route, $parseData] = [$routeParseArrayItem[0], $routeParseArrayItem[1]];
        /**
         * $parameterNames =  [ 0 => 'user', ...]
         */
        $parameterNames = [];
        $parameterValues = [];
        // берем имена параметров из парсинга
        foreach ($parseData['vars'] as $name) {
            array_push($parameterNames, $name);
        }
        // ищем совпадения
        if (preg_match("#{$parseData['pattern']}#", $requestUri, $matches)) {
            /**
             *  $matches = array [
             *      0 => "/home/admin/"
             *      1 => "admin"
             *    ]
             */
            //убираю первый обьект в массиве так как он мешает
            array_shift($matches);
            //присваиваю значение параметра
            $parameterValues = $matches;
            // заполняем пустые значения
            /**
             *  FIX ME  в будущем расчет брать больше 1 параметра
             */
            $parameterValues = array_merge(
                $parameterValues,
                array_fill(
                    count($parameterNames) - count($parameterValues),
                    count($parameterNames) - 1,
                    null
                )
            );

            $vars = array_combine($parameterNames, $parameterValues);
            // проверка метода
            if (!$route->method === $requestMethod) {
                return [self::METHOD_NOT_ALLOWED, null, null];
            }

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
        return false;
    }
}
