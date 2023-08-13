<?php

namespace App\Controller\Admin;

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
}
