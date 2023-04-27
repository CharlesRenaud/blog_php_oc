<?php

namespace App\Entities;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entities\Comment;
use App\Entities\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coverImage;

    /**
     * @ORM\Column(type="text")
     */
    private $externalUrl;

    /**
     * @ORM\Column(type="text")
     */
    private $claim;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getComments()
    {
        return $this->comments;
    }
    public function getValidatedComments()
    {
        return $this->comments->filter(function (Comment $comment) {
            return $comment->getIsValidated();
        });
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    public function setCoverImage(String $coverImage)
    {
        $this->coverImage = $coverImage;
    }
    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(String $externalUrl)
    {
        $this->externalUrl = $externalUrl;
    }
    public function getClaim()
    {
        return $this->claim;
    }

    public function setClaim(String $claim)
    {
        $this->claim = $claim;
    }
}
