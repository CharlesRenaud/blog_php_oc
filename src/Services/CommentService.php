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

    /**
     * @param int $postId
     * @return Comment[]
     */
    public function getCommentsByPostId(int $postId): array
    {
        return $this->entityManager->getRepository(Comment::class)->findBy(['post' => $postId]);
    }

    /**
     * @param int $postId
     * @return Comment[]
     */
    public function getValidatedCommentsByPostId(int $postId): array
    {
        return $this->entityManager->getRepository(Comment::class)->findBy(['post' => $postId, 'isValidated' => true]);
    }

    /**
     * @param string $content
     * @param int $postId
     * @param int $authorId
     * @return Comment
     */
    public function createComment(string $content, int $postId, int $authorId): Comment
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

    /**
     * @param int $commentId
     */
    public function validateComment(int $commentId): void
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($commentId);
        if ($comment) {
            $comment->setIsValidated(true);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        }
    }

    /**
     * @param int $commentId
     */
    public function deleteComment(int $commentId): void
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($commentId);
        if ($comment) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }
    }
}
