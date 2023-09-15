<?php

namespace App\Controller\Admin;

use Berlioz\FlashBag\FlashBag;

class DashboardController extends AbstractCrudController
{
    public function index()
    {
        if ($this->app->session->get('user') === null) {
            return $this->redirect('login');
        }

        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        return $this->render(
            'admin/dashboard.html.twig',
            []
        );
    }
}