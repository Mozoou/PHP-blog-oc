<?php

namespace App\Model;

use Core\Database\Db;

class Post extends Model
{

    /** @var integer $id */
    protected int $id;

    /** @var string $title */
    protected string $title;

    /** @var string $slug */
    protected string $slug;

    /** @var string $content */
    protected string $content;

    /** @var string $image */
    protected ?string $image = null;

    /** @var int $author */
    protected int $author;

    protected string $created_at;

    protected string $updated_at;

    public static function getTable(): string
    {
        return 'post';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): string
    {
        return htmlspecialchars_decode($this->content);
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(string $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getValidComments(): array
    {
        $comments = Db::getInstance()->fetchAllWithWhere(Comment::class, 'post_id', '=', $this->getId(), 'DESC');

        foreach ($comments as $itr => $comment) {
            if ($comment->getStatus() !== Comment::STATUS_VALID) {
                unset($comments[$itr]);
            }
        }

        return $comments;
    }
}
