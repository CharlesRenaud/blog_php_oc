<?php 

namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Entities\Comment;
use App\Entities\Post;
use App\Entities\User;

class CommentService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getValidatedCommentsByPostId($postId)
    {
        return $this->entityManager->getRepository(Comment::class)->findBy(['post' => $postId, 'isValidated' => true]);
    }
}