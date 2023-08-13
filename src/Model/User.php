<?php

namespace App\Model;

class User extends Model
{

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    /** @var integer $id */
    protected int $id;

    /** @var string $fname */
    protected string $fname;

    /** @var string $lname */
    protected string $lname;

    /** @var string $pseudo */
    protected string $pseudo;

    /** @var string $email */
    protected string $email;

    /** @var string $password */
    protected string $password;

    /** @var string $roles */
    protected string $roles = '';

    public static function getTable(): string
    {
        return 'user';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFname(): string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFname().' '.$this->getLname();
    }
}
