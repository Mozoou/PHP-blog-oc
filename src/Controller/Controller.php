<?php

namespace App\Controller;

use Core\Database\Db;
use Twig\Environment;
use Cocur\Slugify\Slugify;
use Berlioz\FlashBag\FlashBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

abstract class Controller
{
    protected ?Db $db = null;

    protected ?FlashBag $flash = null;

    protected ?Environment $twig = null;

    protected ?Slugify $slugify = null;

    protected ?Session $session = null;

    protected ?Request $request = null;

    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->flash = new FlashBag;
        $loader = new FilesystemLoader('../template/');
        $this->twig = new Environment($loader, [
            'cache' => '../var/cache/twig',
            'debug' => true
        ]);
        $this->twig->addExtension(new DebugExtension);
        $this->slugify = new Slugify();
        $this->session = new Session();
        $this->request = Request::createFromGlobals();
    }

    public function render(string $path, ?array $params = []): void
    {
        echo $this->twig->render($path, [
            ...$params,
            'user' => $this->session->get('user', null),
            'flashes' => $this->flash->all(),
        ]);
    }
}