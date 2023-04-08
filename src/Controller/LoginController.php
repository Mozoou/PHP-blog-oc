<?php

namespace App\Controller;

use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class LoginController extends Controller
{
    public function login(array $form): string | bool
    {
        if (array_key_exists('user', $_SESSION)) {
            header('Location: /');
            exit();
        }

        $submited = htmlspecialchars(trim($form['submitted']));

        if (!$submited) {
            echo $this->render('login/form.html.twig');
        }

        // Sanitize inputs
        $email = htmlspecialchars(trim($form['email']));
        $password = htmlspecialchars(trim($form['password']));

        // Check if username and password are not empty
        if (empty($email) || empty($password)) {
            header('Location: /login');
            $this->flash->add(FlashBag::TYPE_ERROR, 'Tout les champs sont requis');
            return false;
        }

        /** @var User|null $user */
        $user = $this->db->fetchOneBy(User::class, ['email' => $email]);

        if (
            $user
            && password_verify($password, $user->getPassword())
        ) {
            // Login successful, set session variables
            session_start();
            $_SESSION['user'] = $user;
            $this->flash->add(FlashBag::TYPE_SUCCESS, 'Connexion réussi');
            header('Location: /');
            return true;
            exit();
        } else {
            // Login failed
            $this->flash->add(FlashBag::TYPE_ERROR, 'L\'adresse mail ou le mot de passe est invalide');
            header('Location: /login');
            return false;
            exit();
        }
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /');
        exit();
    }
}
