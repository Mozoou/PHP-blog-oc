<?php

namespace App\Controller;

use Core\Database\Db;
use Twig\Environment;
use Core\Router\Router;
use Cocur\Slugify\Slugify;
use Berlioz\FlashBag\FlashBag;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

abstract class Controller
{
    protected ?Db $db = null;
    protected ?FlashBag $flash = null;
    protected ?Environment $twig = null;
    protected ?Router $router = null;
    protected ?Slugify $slugify = null;

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
        $this->router = Router::getInstance();
        $this->slugify = new Slugify();
    }

    public function render(string $path, ?array $params = []): string
    {
        return $this->twig->render($path, [
            ...$params,
            'user' => array_key_exists('user', $_SESSION) ? $_SESSION['user'] : null,
            'flashes' => $this->flash->all(),
        ]);
    }
}