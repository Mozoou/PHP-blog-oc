<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\User;

abstract class AbstractCrudController extends Controller
{
    abstract public function index();

    protected function isAdmin(): bool
    {
        return $this->isGranted($this->app->session->get('user'), User::ROLE_ADMIN);
    }
}