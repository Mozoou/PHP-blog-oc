<?php

namespace App\Controller\Admin;

use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class UserCrudController extends AbstractCrudController
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        /* @var array $posts */
        $users = $this->app->db->fetchAll(User::class, 'DESC');

        return $this->render(
            'admin/user/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    public function edit()
    {
        // vérifier si l'utilisateur est bien connecté
        if ($this->app->session->get('user') === null) {
            return $this->redirect('login');
        }

        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        $_id = $this->app->request->get('id');
        $user = $this->app->db->fetchOneById(User::class, (int) $_id);

        $data = [
            'fname' => $this->app->request->request->get('fname'),
            'lname' => $this->app->request->request->get('lname'),
            'pseudo' => $this->app->request->request->get('pseudo'),
            'roles' => $this->app->request->request->get('roles'),
            'submitted' => $this->app->request->request->get('submitted'),
        ];

        $data['roles'] = json_encode([$data['roles']], JSON_THROW_ON_ERROR);


        $submited = htmlspecialchars(trim($data['submitted']));

        if ($submited === '' || $submited === '0') {
            return $this->render('admin/user/edit.html.twig', [
                'userToEdit' => $user,
            ]);
        }

        unset($data['submitted']);
        $user = new User();
        $user->setDataFromArray($data);

        if ($user && $submited) {
            $this->app->db->update($user, $_id);
            return $this->index();
        }
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        $_id = $this->app->request->get('id');
        $user = $this->app->db->fetchOneById(User::class, (int) $_id);

        if ($user) {
            $this->app->db->delete($user, $_id);
            return $this->index();
        }
    }
}
