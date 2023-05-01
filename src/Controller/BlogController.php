<?php

namespace App\Controller;

use App\Model\Post;
use App\Model\User;
use Berlioz\FlashBag\FlashBag;

class BlogController extends Controller
{
    /**
     * Retreive all the blogpost
     * 
     *
     */
    public function index()
    {
        /* @var array $posts */
        $posts = $this->app->db->fetchAll(Post::class, 'DESC');

        return $this->render(
            'blog/index.html.twig',
            [
                'posts' => $posts,
            ]
        );
    }

    /**
     * View one post
     * 
     * @return void
     */
    public function view(): void
    {
        $_id = $this->app->request->get('id');
        if ($_id !== null) {
            $post = $this->app->db->fetchOneById(Post::class, (int) $_id);
            if ($post) {
                $this->render('blog/view.html.twig', [
                    'post' => $post,
                ]);
            } else {
                // post not found
            }
        } else {
            // Invalid url
        }
    }

    /**
     * Create a new post
     * 
     * @return mixed
     */
    public function new(): mixed
    {
        $data = [
            'title' => $this->app->request->request->get('title'),
            'file' => $this->app->request->request->get('file'),
            'content' => $this->app->request->request->get('content'),
            'submitted' => $this->app->request->request->get('submitted'),
        ];

        /** @var User $user */
        $user = null;
        // vérifier si l'utilisateur est bien connecté
        if ($this->app->session->get('user') === null) {
            return $this->redirect('login');
        }

        $user = $this->app->session->get('user');

        $submited = htmlspecialchars(trim($data['submitted']));

        if (!$submited) {
            return $this->render('blog/new.html.twig');
        }

        $data = [
            'title' => htmlspecialchars(trim($data['title'])),
            'image' => htmlspecialchars(trim($data['file'])),
            'slug' => $this->app->slugify->slugify(htmlspecialchars(trim($data['title']))),
            'content' => htmlspecialchars(trim($data['content'])),
            'author' => $user->getId(),
        ];

        $post = new Post();
        $post->setDataFromArray($data);


        try {
            $id = $this->app->db->insert($post);
            return $this->redirect('blog/view', ['id' => $id]);
        } catch (\Throwable $th) {
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Il y a eu une erreur lors de l\'enregistrement.');
            return $this->render('blog/new.html.twig');
        }
    }

    // public function updatePost($id, $title, $content)
    // {
    //     $stmt = $this->app->db->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?');
    //     return $stmt->execute([$title, $content, $id]);
    // }

    // public function deletePost($id)
    // {
    //     $stmt = $this->app->db->prepare('DELETE FROM posts WHERE id = ?');
    //     return $stmt->execute([$id]);
    // }
}
