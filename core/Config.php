<?php

namespace Core;

class Config
{
    private array $settings;

    private static ?self $_instance = null;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->settings = require '../config/config.php'; 
    }

    public function get(string $key): ?string
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : null;
    } 
}

