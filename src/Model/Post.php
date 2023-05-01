<?php

namespace App\Model;

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
    protected string $image;

    /** @var integer $author */
    protected int $author;

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

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAuthor(): int
    {
        return $this->author;
    }

    public function setAuthor(int $author): self
    {
        $this->author = $author;

        return $this;
    }
}
