<?php

namespace Core;

use Core\Database\Db;
use Twig\Environment;
use Core\Router\Router;
use Cocur\Slugify\Slugify;
use Berlioz\FlashBag\FlashBag;
use Core\Mailer\Mailer;
use Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class App
{

    /** @var Dotenv $dotenv */
    public ?Dotenv $dotenv = null;

    /** @var Db $db */
    public ?Db $db = null;

    /** @var FlashBag $flash */
    public ?FlashBag $flash = null;

    /** @var Router $router */
    public ?Router $router = null;
    
    /** @var Environment $twig */
    public ?Environment $twig = null;

    /** @var Slugify $slugify */
    public ?Slugify $slugify = null;

    /** @var Session $session */
    public ?Session $session = null;

    /** @var Request $request */
    public ?Request $request = null;

    /** @var Mailer $mailer */
    public ?Mailer $mailer = null;

    private static ?self $_instance = null;

    /**
     * Retreive the instance of the App (singelton)
     * 
     * @return App
     */
    public static function getInstance(): self
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
        $this->mailer = Mailer::getInstance();
    }
}