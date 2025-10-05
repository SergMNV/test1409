<?php

namespace App\Router;

use Exception;

final class Router
{
    private array $currentRouteParameters = [];

    public function __construct(
        private DataGenerator $dataGenerator,
        private Dispatcher $dispatcher,
    ) {}

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $path = $this->normalisePath($path);
        /**
         * обьект Route создается сохраняется в DataGenerator
         * и возвращаеться обратно для дальнейших манипуляций
         */
        return $this->dataGenerator->addRoute($method, $path, $handler);
    }

    public function dispatch(string $requestMethod, string $requestUri): array
    {
        $requestUri = $this->normalisePath($requestUri);

        // получаем массив роутов для обьекта Dispatcher
        $routes = $this->dataGenerator->getData();

        try {
            $dispatchResult = $this->dispatcher->dispatch($requestMethod, $requestUri, $routes);
            /**
             * проверка что в dispatchResul параметры это массив а не null
             */
            // FIX ME (поменять нуль на пустой массив)
            if (is_array($dispatchResult[2])) {
                $this->currentRouteParameters = array_merge($this->currentRouteParameters, $dispatchResult[2]);
            }
        } catch (Exception $e) {
            /**
             * FIX ME разобраться с обработкой ошибок
             */
            throw new Exception('dispatch error');
        }

        return $dispatchResult;
    }

    public function parameters(array $data = []): array
    {
        /**
         * устанавливаем и запрашиваем параметры
         *  FIX ME нужно ли это?
         */
        if (!empty($data)) {
            $this->currentRouteParameters = array_merge($this->currentRouteParameters, $data);
        }

        return $this->currentRouteParameters;
    }

    public function redirect(string $path): void
    {
        $path = $this->normalisePath($path);
        header("Location: {$path}");
        exit;
    }

    private function normalisePath(string $path): string
    {
        $path = trim(strtolower($path), '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path,);

        return $path;
    }
}
