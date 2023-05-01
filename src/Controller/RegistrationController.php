<?php

namespace App\Controller;

use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class RegistrationController extends Controller
{
    public function index()
    {
        if ($this->app->session->get('user')) {
            $this->redirect();
        }

        return $this->render('registration/form.html.twig');
    }

    public function register()
    {
        $form = [
            'fname' => $this->app->request->request->get('fname'),
            'lname' => $this->app->request->request->get('lname'),
            'pseudo' => $this->app->request->request->get('pseudo'),
            'email' => $this->app->request->request->get('email'),
            'password' => $this->app->request->request->get('password'),
        ];
        $user = $this->verify($form);
        $success = false;
        if ($user) {
            $success = $this->app->db->insert($user);
        } else {
            return;
        }

        if ($success) {
            $this->app->flash->add(FlashBag::TYPE_SUCCESS, 'Inscrit avec succès');
        } else {
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Erreur lors de l\'inscription');
        }

        return $this->render('registration/form.html.twig');
    }

    private function verify(array $data): ?User
    {
        $errors = [];

        // Vérification du champ "Prénom"
        if (empty($data['fname'])) {
            $errors[] = "Le champ 'Prénom' est obligatoire";
        } elseif (!preg_match('/^[a-zA-Z]+$/', $data['fname'])) {
            $errors[] = "Le champ 'Prénom' ne doit contenir que des lettres";
        }

        // Vérification du champ "Nom"
        if (empty($data['lname'])) {
            $errors[] = "Le champ 'Nom' est obligatoire";
        } elseif (!preg_match('/^[a-zA-Z]+$/', $data['lname'])) {
            $errors[] = "Le champ 'Nom' ne doit contenir que des lettres";
        }

        // Vérification du champ "Pseudo"
        if (empty($data['pseudo'])) {
            $errors[] = "Le champ 'Pseudo' est obligatoire";
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $data['pseudo'])) {
            $errors[] = "Le champ 'Pseudo' ne doit contenir que des lettres et des chiffres";
        }

        // Vérification du champ "Email"
        if (empty($data['email'])) {
            $errors[] = "Le champ 'Email' est obligatoire";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Le champ 'Email' doit correspondre à un format valide";
        }

        // Vérification du champ "Mot de passe"
        if (empty($data['password'])) {
            $errors[] = "Le champ 'Mot de passe' est obligatoire";
        } elseif (strlen($data['password']) < 8) {
            $errors[] = "Le champ 'Mot de passe' doit contenir au moins 8 caractères";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $data['password'])) {
            $errors[] = "Le champ 'Mot de passe' doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial";
        }

        // Si des erreurs ont été détectées, affichez-les
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->app->flash->add(FlashBag::TYPE_ERROR, $error);
            }
            return null;
        } else {
            // Sinon, traitez les données du formulaire
            $data = [
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'pseudo' => $data['pseudo'],
                'email' => $data['email'],
                'password' => $data['password'],
            ];

            $user = new User();

            foreach ($data as $method => $value) {
                if ('password' === $method) {
                    $method = 'set' . ucfirst($method);
                    $value = password_hash($value, null, []);
                    $user->$method($value);
                } else {
                    $method = 'set' . ucfirst($method);
                    $user->$method($value);
                }
            }

            return $user;
        }
    }
}
