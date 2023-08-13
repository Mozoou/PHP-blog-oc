<?php

namespace App\Controller\Admin;

use App\Model\Comment;
use App\Model\Post;

class BlogCrudController extends AbstractCrudController
{
    public function index()
    {
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
        $_id = $this->app->request->get('id');
        $comment = $this->app->db->fetchOneById(Comment::class, (int) $_id);

        if ($comment) {
            $this->app->db->delete($comment, $_id);
            return $this->commentsIndex();
        }
    }
}
