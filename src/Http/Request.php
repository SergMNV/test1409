<?php

namespace App\Http;

class Request
{
    private static Request $instance;

    private function __construct(
        public array $server,
        public array $cookie,
        public array $files,
        public array $get,
        public array $post,
    ) {}

    private function __clone() {}

    public static function setGlobals(): static
    {
        return new static(
            $_SERVER,
            $_COOKIE,
            $_FILES,
            $_GET,
            $_POST,
        );
    }
}
