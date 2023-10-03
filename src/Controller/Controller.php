<?php

namespace App\Controller;

use App\Model\User;
use Core\App;

abstract class Controller
{

    /** @var App $app */
    protected ?App $app = null;

    public function __construct()
    {
        $this->app = App::getInstance();
    }

    /** 
     * Render a template from a path
     * 
     * @param string $path Path parameter
     * @param ?array $params Params parameter
     */
    public function render(string $path, ?array $params = [])
    {
        echo $this->app->twig->render(
            $path,
            [
                ...$params,
                'user' => htmlspecialchars((string) $this->app->session->get('user', null)),
                'flashes' => $this->app->flash->all(),
            ]
        );
    }

    /**
     * Redirect to a route with optional params
     * 
     * @param string $path Path parameter
     * @param ?array $params Params parameter 
     */
    public function redirect(string $path = '', ?array $params = [])
    {
        $paramsToString = '';
        $i = 0;
        foreach ($params as $key => $value) {
            if ($i === 0) {
                $paramsToString .= '?';
            }

            if ($i > 0) {
                $paramsToString .= '&';
            }

            $paramsToString .= $key . '=' . $value;
            $i++;
        }
        return header("Location: /$path$paramsToString");
    }

    protected function isGranted(User $user, string $role): bool
    {
        return in_array($role, json_decode((string) $user->getRoles(), null, 512, JSON_THROW_ON_ERROR));
    }
}
