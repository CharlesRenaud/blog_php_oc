<?php

namespace App\Services;

require "./src/Entities/Post.php";
require "./src/Entities/Comment.php";
require "./src/Entities/User.php";

use App\Entities\Post;
use App\Services\CommentService;
use Doctrine\ORM\EntityManager;

class PostService
{
    private $entityManager;
    private $commentService;

    public function __construct(EntityManager $entityManager, CommentService $commentService)
    {
        $this->entityManager = $entityManager;
        $this->commentService = $commentService;
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
        if($validatedComments){
            $post->setComments($validatedComments);
        }
        return $post;
    }
}
