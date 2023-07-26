<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     */
    private User $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isValidated;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private Post $post;
    

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getIsValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): void
    {
        $this->isValidated = $isValidated;
    }
}
