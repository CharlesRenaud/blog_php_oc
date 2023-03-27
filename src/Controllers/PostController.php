<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Twig\Environment;
use App\Services\PostService;
use App\Services\CommentService;
use App\Services\UserService;

class PostController
{
    private $twig;
    private $postService;
    private $commentService;
    private $userService;

    public function __construct(Environment $twig, PostService $postService,  CommentService $commentService,  UserService $userService)
    {
        $this->twig = $twig;
        $this->postService = $postService;
        $this->commentService = $commentService;
        $this->userService = $userService;
    }

    public function AllPosts(): Response
    {
        $posts = $this->postService->getAllPosts();
        $html = $this->twig->render('postlist.html.twig', ['posts' => $posts]);
        return new Response($html);
    }
    public function Post($postId): Response
    {
        $post = $this->postService->getPostWithComments($postId);
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        $isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
        $isAuthenticated = isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : false;
        $html = $this->twig->render('singlepost.html.twig', [
            'post' => $post,
            'userId' => $userId,
            'isAdmin' => $isAdmin,
            'isAuthenticated' => $isAuthenticated
        ]);
        return new Response($html);
    }
    public function addComment(Request $request, $match)
    {
        $postId = $match["params"]["id"];
        $content = $request->request->get('content');
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        $isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
        $isAuthenticated = isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : false;
        $errors = [];
        if (empty($content)) {
            $errors[] = "Le message est vide, erreur !";
        }
        $post = $this->postService->getPostWithComments($postId);
        if (!empty($errors)) {
            if ($post != null) {
                return new Response($this->twig->render('singlepost.html.twig', [
                    'post' => $post,
                    'userId' => $userId,
                    'isAdmin' => $isAdmin,
                    'isAuthenticated' => $isAuthenticated,
                    'errors' => "Contenu manquant"
                ]));
            } else {
                return new Response($this->twig->render('singlepost.html.twig', [
                    'post' => $post,
                    'userId' => $userId,
                    'isAdmin' => $isAdmin,
                    'isAuthenticated' => $isAuthenticated
                ]));
            }
        } else {
            $authorId = $this->userService->getUser($_SESSION['user_id']);
            $this->commentService->createComment($content, $postId, $authorId);
            $_SESSION['success'] = "Commentaire créé avec succès";
            return new RedirectResponse('/blog_php_oc/post/' . $postId);
        }
    }


    public function validateComment(Request $request, $match): Response
    {
        $commentId = $match['params']['id'];
        $this->commentService->validateComment($commentId);
        $_SESSION['success'] = "Commentaire validé avec succès";
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    public function deleteComment(Request $request, $match): Response
    {
        $commentId = $match['params']['id'];
        $this->commentService->deleteComment($commentId);
        $_SESSION['success'] = "Commentaire supprimé avec succès";
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
