<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class DashboardController extends Controller
{
    public function index()
    {
        if ($this->app->session->get('user') === null) {
            return $this->redirect('login');
        }

        if (!$this->isGranted($this->app->session->get('user', null), User::ROLE_ADMIN)) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        return $this->render(
            'admin/dashboard.html.twig',
            []
        );
    }
}