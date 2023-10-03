<?php

namespace App\Controller;

use App\Model\Comment;
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
                    'comments' => $post->getValidComments(),
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

        if ($submited === '' || $submited === '0') {
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
        $post->setCreatedAt((new \DateTimeImmutable())->format('Y-m-d'));
        $post->setUpdatedAt((new \DateTimeImmutable())->format('Y-m-d'));

        try {
            $id = $this->app->db->insert($post);
            return $this->redirect('blog/view', ['id' => $id]);
        } catch (\Throwable) {
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Il y a eu une erreur lors de l\'enregistrement.');
            return $this->render('blog/new.html.twig');
        }
    }

    public function edit()
    {
        $_id = $this->app->request->get('id');
        $post = $this->app->db->fetchOneById(Post::class, (int) $_id);
        /** @var User $user */
        $user = $this->app->session->get('user');
        if ($user === null) {
            return $this->redirect('login');
        }

        if ($user->getId() !== $post->getAuthor()->getId()
            && $this->isGranted($user, User::ROLE_USER)
        ) {
            return $this->redirect('blog');
        }

        $data = [
            'title' => $this->app->request->request->get('title'),
            'image' => $this->app->request->request->get('image'),
            'content' => $this->app->request->request->get('content'),
            'submitted' => $this->app->request->request->get('submitted'),
        ];

        
        // vérifier si l'utilisateur est bien connecté
        if ($this->app->session->get('user') === null) {
            return $this->redirect('login');
        }

        $submited = htmlspecialchars(trim($data['submitted']));

        if ($submited === '' || $submited === '0') {
            return $this->render('blog/edit.html.twig', [
                'post' => $post,
            ]);
        }

        unset($data['submitted']);
        $post = new Post();
        $post->setDataFromArray($data);
        $post->setUpdatedAt((new \DateTimeImmutable())->format('Y-m-d'));

        if ($post && $submited) {
            $this->app->db->update($post, $_id);
            return $this->index();
        }
    }

    public function delete()
    {
        $_id = $this->app->request->get('id');
        $post = $this->app->db->fetchOneById(Post::class, (int) $_id);
        $user = $this->app->session->get('user');

        if ($user->getId() !== $post->getAuthor()->getId()
            && $this->isGranted($user, User::ROLE_USER)
        ) {
            return $this->redirect('blog');
        }

        if ($post) {
            $this->app->db->delete($post, $_id);
            return $this->index();
        }
    }

    public function addComment()
    {
        $data = [
            'post_id' => htmlspecialchars(trim($this->app->request->query->get('id'))),
            'content' => htmlspecialchars(trim($this->app->request->request->get('comment'))),
            'author' => htmlspecialchars(trim($this->app->request->request->get('author'))),
        ];

        $comment = new Comment();
        $comment->setStatus(Comment::STATUS_PENDING);
        $comment->setDataFromArray($data);

        try {
            $this->app->db->insert($comment);
            $this->app->flash->add(FlashBag::TYPE_SUCCESS, 'Le commentaire est en attente de validation');
        } catch (\Throwable) {
            $this->app->flash->add(FlashBag::TYPE_ERROR, 'Il y a eu une erreur lors de l\'ajout du commentaire.');
        }

        return $this->redirect('blog/view', ['id' => $data['post_id']]);

    }

    public function deleteComment()
    {
        // Implements this method
    }
}
