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

    public function getCommentsByPostId($postId)
    {
        return $this->entityManager->getRepository(Comment::class)->findBy(['post' => $postId]);
    }
    public function getValidatedCommentsByPostId($postId)
    {
        return $this->entityManager->getRepository(Comment::class)->findBy(['post' => $postId, 'isValidated' => true]);
    }
    public function createComment($content, $postId, $authorId)
    {
        $author = $this->entityManager->getRepository(User::class)->find($authorId);
        $post = $this->entityManager->getRepository(Post::class)->find($postId);

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTime());
        $comment->setAuthor($author);
        $comment->setPost($post);
        if ($author->getIsAdmin() === true) {
            $comment->setIsValidated(true);
        } else {
            $comment->setIsValidated(false);
        }
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        return $comment;
    }
    public function validateComment($commentId)
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($commentId);
        if ($comment) {
            $comment->setIsValidated(true);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        }
    }

    public function deleteComment($commentId)
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($commentId);
        if ($comment) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }
    }
}
