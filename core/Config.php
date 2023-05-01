<?php

namespace Core;

use Dotenv\Dotenv;

class Config
{
    private array $settings;

    private static ?self $_instance = null;

    private ?Dotenv $dotenv = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
        $this->dotenv->load();
    }

    public function get(string $key): ?string
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : null;
    }
}
