<?php

use App\Router;

if (!function_exists('router')) {
    function router(array $options = []): Router\Router
    {
        $options += $options = [
            'router' => 'App\Router\SimpleRouter',
            'routeCollector' => 'App\Router\SimpleRouteCollector',
            'routeParser' => '',
            'dataGenerator' => '',
            'dispatcher' => '',

        ];

        $dataGenerator = new $options['dataGenerator'](
            new $options['routeCollector'](),
            new $options['routeParser'](),
        );

        $dispatcher = new $options['dispatcher']();

        return new $options['router']($dataGenerator, $dispatcher);
    }
}
