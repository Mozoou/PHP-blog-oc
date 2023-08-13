<?php

namespace Config\routing;

use App\Controller\Admin\BlogCrudController;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\UserCrudController;
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
$router->get('/blog/edit', BlogController::class, 'edit');
$router->post('/blog/edit', BlogController::class, 'edit');
$router->get('/blog/delete', BlogController::class, 'delete');
$router->post('/blog/comment/add', BlogController::class, 'addComment');
$router->get('/blog/comment/delete', BlogController::class, 'deleteComment');

$router->get('/', HomeController::class, 'index');

$router->post('/register', RegistrationController::class, 'register');
$router->get('/register', RegistrationController::class, 'index');

$router->get('/admin', DashboardController::class, 'index');
$router->get('/admin/blog', BlogCrudController::class, 'index');
$router->get('/admin/blog/comments', BlogCrudController::class, 'commentsIndex');
$router->get('/admin/blog/comments/validate', BlogCrudController::class, 'validateComment');
$router->get('/admin/blog/comments/delete', BlogCrudController::class, 'deleteComment');
$router->get('/admin/users', UserCrudController::class, 'index');
$router->get('/admin/users/edit', UserCrudController::class, 'edit');
$router->post('/admin/users/edit', UserCrudController::class, 'edit');
$router->get('/admin/users/delete', UserCrudController::class, 'delete');

$router->get('/login', LoginController::class, 'login');
$router->post('/login', LoginController::class, 'login');
$router->get('/logout', LoginController::class, 'logout');

$router->run();
