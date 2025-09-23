<?php

namespace App\Http;

class Request
{
    private function __construct(
        public readonly array $server,
        public readonly array $cookie,
        public readonly array $files,
        public readonly array $get,
        public readonly array $post,
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
