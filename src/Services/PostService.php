<?php

namespace App\Services;

require "./src/Entities/Post.php";
require "./src/Entities/Comment.php";
require "./src/Entities/User.php";

use App\Entities\Post;
use App\Entities\User;
use App\Services\CommentService;
use App\Services\UserService;
use Doctrine\ORM\EntityManager;

class PostService
{
    private $entityManager;
    private $commentService;
    private $userService;

    public function __construct(EntityManager $entityManager, CommentService $commentService, userService $userService)
    {
        $this->entityManager = $entityManager;
        $this->commentService = $commentService;
        $this->userService = $userService;
    }

    public function getAllPosts()
    {
        return $this->entityManager->getRepository(Post::class)->findAll();
    }

    public function getPostById($postId)
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    public function getPostWithValidatedComments($postId)
    {
        $post = $this->getPostById($postId);
        $validatedComments = $this->commentService->getValidatedCommentsByPostId($postId);
        if ($validatedComments) {
            $post->setComments($validatedComments);
        }
        return $post;
    }

    public function getPostWithComments($postId)
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    public function createPost($title, $content, $author, $coverImage)
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($author);
        $post->setCreatedAt(new \DateTime());
        if ($coverImage) {
            $post->setCoverImage($coverImage);
        }
        $this->entityManager->persist($post);
        $this->entityManager->flush();
        return $post;
    }
    

    public function updatePost($postId, $title, $content, $coverImage)
    {
        $post = $this->entityManager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw new \Exception('Post not found');
        }

        $post->setTitle($title);
        $post->setContent($content);
        $post->setCoverImage($coverImage);
        $this->entityManager->flush();
        return $post;
    }

    public function deletePost($postId)
    {
        $post = $this->entityManager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw new \Exception('Post not found');
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

}
