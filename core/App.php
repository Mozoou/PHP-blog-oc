<?php

namespace Core;

use Core\Database\Db;
use Twig\Environment;
use Core\Router\Router;
use Cocur\Slugify\Slugify;
use Berlioz\FlashBag\FlashBag;
use Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class App
{
    public ?Dotenv $dotenv = null;

    public ?Db $db = null;

    public ?FlashBag $flash = null;

    public ?Router $router = null;
    
    public ?Environment $twig = null;

    public ?Slugify $slugify = null;

    public ?Session $session = null;

    public ?Request $request = null;

    private static ?self $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
        $this->dotenv->load();
        $this->db = Db::getInstance();
        $this->flash = new FlashBag();
        $loader = new FilesystemLoader('../template/');
        $this->twig = new Environment($loader, [
            'cache' => '../var/cache/twig',
            'debug' => true
        ]);
        $this->twig->addExtension(new DebugExtension());
        $this->slugify = new Slugify();
        $this->session = new Session();
        $this->request = Request::createFromGlobals();
    }
}