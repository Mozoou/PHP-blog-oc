<?php

namespace App\Controller\Admin;

use App\Model\Comment;
use App\Model\Post;
use Berlioz\FlashBag\FlashBag;

class BlogCrudController extends AbstractCrudController
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        /* @var array $posts */
        $posts = $this->app->db->fetchAll(Post::class, 'DESC');

        return $this->render(
            'admin/blog/index.html.twig',
            [
                'posts' => $posts,
            ]
        );
    }

    public function commentsIndex()
    {
        /* @var array $comments */
        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        $comments =  $this->app->db->fetchAllWithWhere(Comment::class, 'status','!=' , Comment::STATUS_VALID, 'DESC');

        return $this->render(
            'admin/blog/comments.html.twig',
            [
                'comments' => $comments,
            ]
        );
    }

    public function validateComment()
    {
        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        $_id = $this->app->request->get('id');
        $comment = $this->app->db->fetchOneById(Comment::class, (int) $_id);

        if ($comment) {
            $comment = new Comment();
            $comment->setStatus(Comment::STATUS_VALID);

            if ($comment) {
                $this->app->db->update($comment, $_id);
                return $this->commentsIndex();
            }
        }
    }

    public function deleteComment()
    {
        if (!$this->isAdmin()) {
            $this->app->flash->add(FlashBag::TYPE_WARNING, 'Vous n\'etes pas connecter en tant qu\'admin');
            return $this->redirect();
        }

        $_id = $this->app->request->get('id');
        $comment = $this->app->db->fetchOneById(Comment::class, (int) $_id);

        if ($comment) {
            $this->app->db->delete($comment, $_id);
            return $this->commentsIndex();
        }
    }
}
