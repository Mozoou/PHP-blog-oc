<?php

namespace App\Controller;

use Core\App;

abstract class Controller
{

    /** @var App $app */
    protected ?App $app = null;

    public function __construct()
    {
        $this->app = App::getInstance();
    }

    public function render(string $path, ?array $params = [])
    {
        echo $this->app->twig->render(
            $path,
            [
                ...$params,
                'user' => htmlspecialchars($this->app->session->get('user', null)),
                'flashes' => $this->app->flash->all(),
            ]
        );
    }

    public function redirect(string $path = '', array $params = [])
    {
        $paramsToString = '?';
        $i = 0;
        foreach ($params as $key => $value) {
            if ($i > 0) {
                $paramsToString .= '&';
            }

            $paramsToString .= $key . '=' . $value;
            $i++;
        }
        header("Location: /$path$paramsToString");
        die();
    }
}
