<?php

namespace Config\routing;

use Core\Router\Router;
use App\Controller\BlogController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\RegistrationController;

$router = new Router();

$router->get('/blog/view', BlogController::class, 'view');
$router->get('/blog', BlogController::class, 'index');
$router->get('/blog/new', BlogController::class, 'new');
$router->post('/blog/new', BlogController::class, 'new');

$router->get('/', HomeController::class, 'index');

$router->post('/register', RegistrationController::class, 'register');
$router->get('/register', RegistrationController::class, 'index');

$router->get('/login', LoginController::class, 'login');
$router->post('/login', LoginController::class, 'login');
$router->get('/logout', LoginController::class, 'logout');

$router->run();
