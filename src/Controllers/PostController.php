<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
        $post = $this->postService->getPostWithValidatedComments($postId);
        $isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
        $isAuthenticated = isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : false;
        $html = $this->twig->render('singlepost.html.twig', [
            'post' => $post,
            'isAdmin' => $isAdmin,
            'isAuthenticated' => $isAuthenticated
        ]);
        return new Response($html);
    }
    public function addComment(Request $request, $match)
    {
        $postId = $match["params"]["id"];
        $content = $request->request->get('content');
        $errors = [];
        if (empty($content)) {
            $errors[] = "Content is required";
        }
        $post = $this->postService->getPostWithValidatedComments($postId);
        if (!empty($errors)) {
            if ($post != null) {
                $validatedComments = $this->commentService->getValidatedCommentsByPostId($postId);
                return new Response($this->twig->render('singlepostwithcomments.html.twig', [
                    'post' => $post,
                    'validatedComments' => $validatedComments,
                    'errors' => "Contenu manquant"
                ]));
            } else {
                $validatedComments = [];
                var_dump($validatedComments);
                return new Response($this->twig->render('singlepostwithcomments.html.twig', [
                    'post' => $post,
                    'validatedComments' => $validatedComments,
                ]));
            }
        } else {
            $authorId = $this->userService->getUser($_SESSION['user_id']);
            $this->commentService->createComment($content, $postId, $authorId);
            $_SESSION['success'] = "Commentaire créé avec succès";
            header("Location: /BlogV1/post/single/" . $postId);
            exit();
        }
    }
}
