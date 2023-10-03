<?php

namespace App\Model;

class Comment extends Model
{
    final public const STATUS_VALID = 'validé';
    final public const STATUS_CANCELED = 'refusé';
    final public const STATUS_PENDING = 'en attente';

    protected int $id;

    protected int $author;

    protected string $content;

    protected string $status;

    protected int $post_id;

    public static function getTable(): string
    {
        return 'comment';
    }

    public function getId(): int
    {
        return $this->id;
    }
 
    public function getAuthor(): User
    {
        return $this->getAssociation($this->author, User::class);
    }

    public function setAuthor(int $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPost_id()
    {
        return $this->post_id;
    }

    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }
}
