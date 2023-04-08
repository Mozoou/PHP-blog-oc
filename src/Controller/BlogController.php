<?php

namespace App\Controller;

use App\Model\Post;
use App\Model\User;

class BlogController extends Controller
{
    public function index(): void
    {
        $posts = $this->db->fetchAll(Post::class, [
            'orderBy' => 'DESC',
        ]);

        echo $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function view(array $params): void
    {
        $id = array_key_exists('id', $params) ? $params['id'] : null;
        if ($id) {
            $post = $this->db->fetchOneById(Post::class, intval($id));
            if ($post) {
                echo $this->render('blog/view.html.twig', [
                    'post' => $post,
                ]);
            } else {
                // post not found
            }
        } else {
            // Invalid url
        }
    }

    public function new(array $data)
    {
        /** @var User $user */
        $user = null;
        // vérifier si l'utilisateur est bien connecté
        if (!array_key_exists('user', $_SESSION)) {
            header('Location: /login');
            exit();
        } else {
            $user = $_SESSION['user'];
        }

        $submited = htmlspecialchars(trim($data['submitted']));

        if (!$submited) {
            echo $this->render('blog/new.html.twig');
            exit();
        }

        $data = [
            'title' => htmlspecialchars(trim($data['title'])),
            'image' => htmlspecialchars(trim($data['file'])),
            'slug' => $this->slugify->slugify(htmlspecialchars(trim($data['title']))),
            'content' => htmlspecialchars(trim($data['content'])),
            'author' => $user->getId(),
        ];

        $post = new Post;
        $post->setDataFromArray($data);

        $success = $this->db->insert($post);

        dd($success);
        return ;
    }

    public function updatePost($id, $title, $content)
    {
        $stmt = $this->db->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?');
        return $stmt->execute([$title, $content, $id]);
    }

    public function deletePost($id)
    {
        $stmt = $this->db->prepare('DELETE FROM posts WHERE id = ?');
        return $stmt->execute([$id]);
    }

}
