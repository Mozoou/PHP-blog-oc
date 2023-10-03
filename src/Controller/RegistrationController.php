<?php

namespace App\Controller;

use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class RegistrationController extends Controller
{
    /** 
     * Register form
     */
    public function index()
    {
        if ($this->app->session->get('user')) {
            $this->redirect();
        }

        return $this->render('registration/form.html.twig');
    }

    /**
     * Processing register form
     */
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
        if ($user instanceof \App\Model\User) {
            $success = $this->app->db->insert($user);
        } else {
            return;
        }

        if ($success !== 0) {
            $this->app->flash->add(FlashBag::TYPE_SUCCESS, 'Inscrit avec succès');
        } else {
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Erreur lors de l\'inscription');
        }

        return $this->render('registration/form.html.twig');
    }

    /**
     * Verify register form data if they are correct
     * 
     * @param array $data Data parameter
     */
    private function verify(array $data): ?User
    {
        $errors = [];

        // Vérification du champ "Prénom"
        if (empty($data['fname'])) {
            $errors[] = "Le champ 'Prénom' est obligatoire";
        } elseif (!preg_match('/^[a-zA-Z]+$/', (string) $data['fname'])) {
            $errors[] = "Le champ 'Prénom' ne doit contenir que des lettres";
        }

        // Vérification du champ "Nom"
        if (empty($data['lname'])) {
            $errors[] = "Le champ 'Nom' est obligatoire";
        } elseif (!preg_match('/^[a-zA-Z]+$/', (string) $data['lname'])) {
            $errors[] = "Le champ 'Nom' ne doit contenir que des lettres";
        }

        // Vérification du champ "Pseudo"
        if (empty($data['pseudo'])) {
            $errors[] = "Le champ 'Pseudo' est obligatoire";
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', (string) $data['pseudo'])) {
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
        } elseif (strlen((string) $data['password']) < 8) {
            $errors[] = "Le champ 'Mot de passe' doit contenir au moins 8 caractères";
        }

        // Si des erreurs ont été détectées, affichez-les
        if ($errors !== []) {
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
            $user->setRoles(json_encode([User::ROLE_USER]));

            foreach ($data as $method => $value) {
                if ('password' === $method) {
                    $method = 'set' . ucfirst($method);
                    $value = password_hash((string) $value, null, []);
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
