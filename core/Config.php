<?php

namespace Core;

use Dotenv\Dotenv;

class Config
{
    private array $settings;

    private static ?self $_instance = null;

    private ?Dotenv $dotenv = null;

    /**
     * Retreive the instance of the Config (singelton)
     * 
     * @return Config
     */
    public static function getInstance(): self
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

    /**
     * Get parameter by key
     * 
     * @param string $key Key parameter
     * @return ?string
     */
    public function get(string $key): ?string
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : null;
    }
}
