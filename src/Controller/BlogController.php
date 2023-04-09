<?php

namespace App\Controller;

use App\Model\Post;
use App\Model\User;

class BlogController extends Controller
{
    public function index()
    {
        /* @var array $posts */
        $posts = $this->db->fetchAll(Post::class, [
            'orderBy' => 'DESC',
        ]);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * View one post
     * @return void
     */
    public function view(): void
    {
        $_id = $this->request->get('id');
        if ($_id) {
            $post = $this->db->fetchOneById(Post::class, (int)$_id);
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
     * @return mixed
     */
    public function new(): mixed
    {
        // $data = $this->request->post->toArray();

        /** @var User $user */
        $user = null;
        // vérifier si l'utilisateur est bien connecté
        if (null === $this->session->get('user')) {
            return $this->router->redirectToRoute('/login');
        }

        $user = $this->session->get('user');

        $submited = htmlspecialchars(trim($data['submitted']));

        if (!$submited) {
            return $this->render('blog/new.html.twig');
        }

        $data = [
            'title' => htmlspecialchars(trim($data['title'])),
            'image' => htmlspecialchars(trim($data['file'])),
            'slug' => $this->slugify->slugify(htmlspecialchars(trim($data['title']))),
            'content' => htmlspecialchars(trim($data['content'])),
            'author' => $user->getId(),
        ];

        $post = new Post();
        $post->setDataFromArray($data);

        return $this->db->insert($post);
    }

    // public function updatePost($id, $title, $content)
    // {
    //     $stmt = $this->db->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?');
    //     return $stmt->execute([$title, $content, $id]);
    // }

    // public function deletePost($id)
    // {
    //     $stmt = $this->db->prepare('DELETE FROM posts WHERE id = ?');
    //     return $stmt->execute([$id]);
    // }
}
