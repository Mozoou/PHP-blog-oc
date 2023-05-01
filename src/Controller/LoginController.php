<?php

namespace App\Controller;

use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class LoginController extends Controller
{
    public function login()
    {
        if ($this->app->session->get('user')) {
            $this->redirect();
        }

        $submited = htmlspecialchars(trim($this->app->request->request->get('submitted')));

        if (!$submited) {
            return $this->render('login/form.html.twig');
        }

        // Sanitize inputs
        $email = htmlspecialchars(trim($this->app->request->request->get('email')));
        $password = htmlspecialchars(trim($this->app->request->request->get('password')));

        // Check if username and password are not empty
        if (empty($email) || empty($password)) {
            header('Location: /login');
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Tout les champs sont requis');
            return false;
        }

        /** @var User|null $user */
        $user = $this->app->db->fetchOneBy(User::class, ['email' => $email]);

        if (
            $user
            && password_verify($password, $user->getPassword())
        ) {
            // Login successful, set session variables
            $this->app->session->set('user', $user);
            $this->app->flash->add(FlashBag::TYPE_SUCCESS, 'Connexion réussi');
            return $this->redirect();
        } else {
            // Login failed
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'L\'adresse mail ou le mot de passe est invalide');
            return $this->redirect('login');
        }
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        $this->redirect();
    }
}
