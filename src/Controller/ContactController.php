<?php

namespace App\Controller;

use Berlioz\FlashBag\FlashBag;

class ContactController extends Controller
{
    /**
     * Retreive the home page
     * 
     */
    public function index()
    {
        return $this->render('contact/index.html.twig');
    }

    public function send()
    {
        $form = [
            'name' => $this->app->request->request->get('name'),
            'fname' => $this->app->request->request->get('fname'),
            'email' => $this->app->request->request->get('email'),
            'content' => $this->app->request->request->get('content'),
            'submit' => $this->app->request->request->get('submit'),
        ];

        $errors = $this->verify($form);

        if (count($errors) === 0) {
            $this->app->mailer->mailer->setFrom($form['email'], $form['name']);
            $this->app->mailer->mailer->addAddress(getenv('MAILER_EMAIL'));
            $this->app->mailer->mailer->isHTML(true);
            $this->app->mailer->mailer->addReplyTo($form['email'], $form['name']);
            $this->app->mailer->mailer->Subject = 'Nouvelle demande de contact';
            $this->app->mailer->mailer->Body = "Bonjour, <br> Vous avez recu une nouvelle demande de <b>".$form['name'].' '.$form['fname'].'</b>, voici son message :<br>'.$form['content'];
            $this->app->mailer->mailer->send();
            $this->app->flash->add(FlashBag::TYPE_SUCCESS, 'L\'email a bien été envoyé');
        } else {
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Erreur lors de le l\'envoie');
        }

        return $this->render('contact/index.html.twig');
    }

    private function verify()
    {
        $errors = [];

        // Vérification du champ "Nom"
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($name)) {
            $errors[] = 'Le champ "Nom" est obligatoire.';
        }

        // Vérification du champ "Prénom"
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($fname)) {
            $errors[] = 'Le champ "Prénom" est obligatoire.';
        }

        // Vérification du champ "Email"
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (empty($email)) {
            $errors[] = 'L\'adresse email n\'est pas valide.';
        }

        // Vérification du champ "Message"
        $content = $_POST['content']; // Pas de filtre ici car c'est un texte
        if (empty($content)) {
            $errors[] = 'Le champ "Message" est obligatoire.';
        }

        return $errors;
    }
}
