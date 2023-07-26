<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="posts")
 */
class Post
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
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private User $author;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"remove"})
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private Collection $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $coverImage;

    /**
     * @ORM\Column(type="text")
     */
    private string $externalUrl;

    /**
     * @ORM\Column(type="text")
     */
    private string $claim;
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getValidatedComments(): Collection
    {
        return $this->comments->filter(function(Comment $comment) {
            return $comment->getIsValidated();
        });
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    public function getExternalUrl(): string
    {
        return $this->externalUrl;
    }
    public function setExternalUrl(string $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
    }

    public function getClaim(): string
    {
        return $this->claim;
    }

    public function setClaim(string $claim): void
    {
        $this->claim = $claim;
    }
}
