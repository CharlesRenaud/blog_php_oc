<?php

<<<<<<< HEAD
declare(strict_types=1);

namespace App\Services;

require './src/Entities/Post.php';

require './src/Entities/Comment.php';

require './src/Entities/User.php';

use App\Entities\Post;
use App\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
=======
namespace App\Services;

require "./src/Entities/Post.php";
require "./src/Entities/Comment.php";
require "./src/Entities/User.php";

use App\Entities\Post;
use App\Entities\User;
use App\Services\CommentService;
use App\Services\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

class PostService
{
    private $entityManager;
<<<<<<< HEAD

    private $commentService;

=======
    private $commentService;
    private $userService;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

    public function __construct(EntityManager $entityManager, CommentService $commentService, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->commentService = $commentService;
<<<<<<< HEAD
=======
        $this->userService = $userService;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    }

    /**
     * @return Post[]
     */
    public function getAllPosts(): array
    {
        return $this->entityManager->getRepository(Post::class)->findAll();
    }

    /**
     * @param int $postId
<<<<<<< HEAD
     *
     * @return null|Post
=======
     * @return Post|null
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     */
    public function getPostById(int $postId): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    /**
     * @param int $postId
<<<<<<< HEAD
     *
     * @return null|Post
=======
     * @return Post|null
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     */
    public function getPostWithValidatedComments(int $postId): ?Post
    {
        $post = $this->getPostById($postId);
        $validatedComments = $this->commentService->getValidatedCommentsByPostId($postId);
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        if ($validatedComments) {
            $commentsCollection = new ArrayCollection($validatedComments);
            $post->setComments($commentsCollection);
        }
<<<<<<< HEAD

        return $post;
    }

    /**
     * @param int $postId
     *
     * @return null|Post
=======
        return $post;
    }


    /**
     * @param int $postId
     * @return Post|null
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     */
    public function getPostWithComments(int $postId): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    /**
     * @param string $title
     * @param string $content
     * @param User $author
<<<<<<< HEAD
     * @param null|string $coverImage
     * @param null|string $externalUrl
     * @param null|string $claim
     *
=======
     * @param string|null $coverImage
     * @param string|null $externalUrl
     * @param string|null $claim
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     * @return Post
     */
    public function createPost(
        string $title,
        string $content,
        User $author,
        ?string $coverImage,
        ?string $externalUrl,
        ?string $claim
    ): Post {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($author);
        $post->setExternalUrl($externalUrl);
        $post->setClaim($claim);
        $post->setCreatedAt(new \DateTime());
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        if ($coverImage) {
            $post->setCoverImage($coverImage);
        }
        $this->entityManager->persist($post);
        $this->entityManager->flush();
<<<<<<< HEAD

        return $post;
    }

=======
        return $post;
    }


>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    /**
     * @param int $postId
     * @param string $title
     * @param string $content
<<<<<<< HEAD
     * @param null|string $coverImage
     * @param null|string $externalUrl
     * @param null|string $claim
     *
     * @throws \Exception
     *
     * @return Post
=======
     * @param string|null $coverImage
     * @param string|null $externalUrl
     * @param string|null $claim
     * @return Post
     * @throws \Exception
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     */
    public function updatePost(
        int $postId,
        string $title,
        string $content,
        ?string $coverImage,
        ?string $externalUrl,
        ?string $claim
    ): Post {
        $post = $this->entityManager->getRepository(Post::class)->find($postId);
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        if (!$post) {
            throw new \Exception('Publication introuvable');
        }

        $post->setTitle($title);
        $post->setContent($content);
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        if ($coverImage) {
            $post->setCoverImage($coverImage);
        }
        $post->setExternalUrl($externalUrl);
        $post->setClaim($claim);
        $this->entityManager->flush();
<<<<<<< HEAD

        return $post;
    }

    /**
     * @param int $postId
     *
=======
        return $post;
    }
    /**
     * @param int $postId
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     * @throws \Exception
     */
    public function deletePost(int $postId): void
    {
        $post = $this->entityManager->getRepository(Post::class)->find($postId);
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        if (!$post) {
            throw new \Exception('Publication introuvable');
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
