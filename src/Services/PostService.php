<?php

declare(strict_types=1);

namespace App\Services;

require './src/Entities/Post.php';

require './src/Entities/Comment.php';

require './src/Entities/User.php';

use App\Entities\Post;
use App\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class PostService
{
    private $entityManager;

    private $commentService;


    public function __construct(EntityManager $entityManager, CommentService $commentService, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->commentService = $commentService;
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
     *
     * @return null|Post
     */
    public function getPostById(int $postId): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    /**
     * @param int $postId
     *
     * @return null|Post
     */
    public function getPostWithValidatedComments(int $postId): ?Post
    {
        $post = $this->getPostById($postId);
        $validatedComments = $this->commentService->getValidatedCommentsByPostId($postId);

        if ($validatedComments) {
            $commentsCollection = new ArrayCollection($validatedComments);
            $post->setComments($commentsCollection);
        }

        return $post;
    }

    /**
     * @param int $postId
     *
     * @return null|Post
     */
    public function getPostWithComments(int $postId): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    /**
     * @param string $title
     * @param string $content
     * @param User $author
     * @param null|string $coverImage
     * @param null|string $externalUrl
     * @param null|string $claim
     *
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

        if ($coverImage) {
            $post->setCoverImage($coverImage);
        }
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param int $postId
     * @param string $title
     * @param string $content
     * @param null|string $coverImage
     * @param null|string $externalUrl
     * @param null|string $claim
     *
     * @throws \Exception
     *
     * @return Post
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

        if (!$post) {
            throw new \Exception('Publication introuvable');
        }

        $post->setTitle($title);
        $post->setContent($content);

        if ($coverImage) {
            $post->setCoverImage($coverImage);
        }
        $post->setExternalUrl($externalUrl);
        $post->setClaim($claim);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param int $postId
     *
     * @throws \Exception
     */
    public function deletePost(int $postId): void
    {
        $post = $this->entityManager->getRepository(Post::class)->find($postId);

        if (!$post) {
            throw new \Exception('Publication introuvable');
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
