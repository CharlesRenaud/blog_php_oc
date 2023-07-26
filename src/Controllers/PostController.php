<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CommentService;
use App\Services\PostService;
use App\Services\UserService;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PostController
{
    private Environment $twig;

    private PostService $postService;

    private CommentService $commentService;

    private UserService $userService;

    public function __construct(Environment $twig, PostService $postService, CommentService $commentService, UserService $userService)
    {
        $this->twig = $twig;
        $this->postService = $postService;
        $this->commentService = $commentService;
        $this->userService = $userService;
    }

    public function allPosts(): Response
    {
        $posts = $this->postService->getAllPosts();
        $html = $this->twig->render('postlist.html.twig', ['posts' => array_reverse($posts)]);

        return new Response($html);
    }

    public function post(int $postId): Response
    {
        $post = $this->postService->getPostWithComments($postId);
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        $isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
        $isAuthenticated = isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : false;
        $html = $this->twig->render('singlepost.html.twig', [
            'post' => $post,
            'userId' => $userId,
            'isAdmin' => $isAdmin,
            'isAuthenticated' => $isAuthenticated,
        ]);

        return new Response($html);
    }

    public function addComment(Request $request, array $match): Response|RedirectResponse
    {
        $postId = $match['params']['id'];
        $content = $request->request->get('content');
        // Valider le contenu du commentaire
        if (!v::notEmpty()->stringType()->validate($content)) {
            $errors[] = 'Le contenu du commentaire est vide ou invalide.';
        }
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        $isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
        $isAuthenticated = isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : false;
        $errors = [];

        if (empty($content)) {
            $errors[] = 'Le message est vide, erreur !';
        }
        $post = $this->postService->getPostWithComments(intval($postId));

        if (!empty($errors)) {
            if ($post != null) {
                return new Response($this->twig->render('singlepost.html.twig', [
                    'post' => $post,
                    'userId' => $userId,
                    'isAdmin' => $isAdmin,
                    'isAuthenticated' => $isAuthenticated,
                    'errors' => 'Contenu manquant',
                ]));
            }

            return new Response($this->twig->render('singlepost.html.twig', [
                'post' => $post,
                'userId' => $userId,
                'isAdmin' => $isAdmin,
                'isAuthenticated' => $isAuthenticated,
            ]));
        }
        $authorId = $_SESSION['user_id'];
        $this->commentService->createComment($content, intval($postId), $authorId);
        $_SESSION['success'] = 'Commentaire créé avec succès';

        header('Location: /post/' . $postId);

        exit();
    }

    public function validateComment(Request $request, array $match): Response
    {
        $commentId = $match['params']['id'];
        $this->commentService->validateComment(intval($commentId));
        $_SESSION['success'] = 'Commentaire validé avec succès';
        $referer = $request->headers->get('referer');

        header('Location: ' . $referer);

        exit();
    }

    public function deleteComment(Request $request, array $match): Response
    {
        $commentId = $match['params']['id'];
        $this->commentService->deleteComment(intval($commentId));
        $_SESSION['success'] = 'Commentaire supprimé avec succès';
        $referer = $request->headers->get('referer');

        header('Location: ' . $referer);

        exit();
    }

    public function addPost(Request $request): Response
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;

        // Si la requête est de type POST
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $externalUrl = $request->request->get('externalUrl');
            $claim = $request->request->get('claim');
            $coverImage = $request->files->get('coverImage');

            if ($coverImage instanceof UploadedFile) {
                $fileName = uniqid() . '.' . $coverImage->guessExtension();
                $coverImage->move('uploads', $fileName);
            } else {
                $fileName = null;
            }

            $errors = [];

            if (empty($title)) {
                $errors[] = 'Le titre est manquant, erreur !';
            }

            if (empty($content)) {
                $errors[] = 'Le contenu est manquant, erreur !';
            }

            // Si il y a des erreurs, on affiche les messages et on garde les champs remplis
            if (!empty($errors)) {
                return new Response($this->twig->render('createpost.html.twig', [
                    'errors' => $errors,
                    'title' => $title,
                    'content' => $content,
                ]));
            }
            $authorId = $this->userService->getUser($userId);
            $post = $this->postService->createPost($title, $content, $authorId, $fileName, $externalUrl, $claim);

            $_SESSION['success'] = 'Post créé avec succès';

            header('Location: /post/' . $post->getId());

            exit();
        }
        // Si la requête est de type GET, on affiche le formulaire vide
        return new Response($this->twig->render('createpost.html.twig'));
    }

    public function editPost(Request $request, array $match): Response
    {
        $postId = $match['params']['id'];
        $post = $this->postService->getPostById(intval($postId));
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        $externalUrl = $request->get('externalUrl');
        $claim = $request->get('claim');
        $coverImage = $request->files->get('coverImage');
        $existingCoverImage = $post->getCoverImage();
        $errors = [];

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            if ($coverImage instanceof UploadedFile) {
                // Enregistrer la nouvelle image de couverture
                $fileName = uniqid() . '.' . $coverImage->guessExtension();
                $coverImage->move('uploads', $fileName);
            } else {
                $fileName = $existingCoverImage;
            }

            if ($userId !== $post->getAuthor()->getId() && !$this->userService->isAdmin($userId)) {
                $errors[] = "Vous n'êtes pas autorisé à modifier ce post !";
            }
            $title = $request->request->get('title');
            $content = $request->request->get('content');

            if (empty($title)) {
                $errors[] = 'Le titre est manquant, erreur !';
            }

            if (empty($content)) {
                $errors[] = 'Le contenu est manquant, erreur !';
            }

            if (!empty($errors)) {
                return new Response($this->twig->render('editpost.html.twig', [
                    'errors' => $errors,
                    'post' => $post,
                    'title' => $title,
                    'content' => $content,
                    'coverImage' => $coverImage,
                ]));
            }
            $this->postService->updatePost(intval($postId), $title, $content, $fileName, $externalUrl, $claim);
            $_SESSION['success'] = 'Post modifié avec succès';

            header('Location: /post/' . $postId);

            exit();
        }
        // Si le formulaire est affiché pour la première fois
        return new Response($this->twig->render('editpost.html.twig', [
            'errors' => $errors,
            'post' => $post,
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'coverImage' => $existingCoverImage,
        ]));
    }

    public function deletePost(array $match): Response
    {
        $postId = $match['params']['id'];
        $post = $this->postService->getPostById(intval($postId));
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        $errors = [];

        if ($userId !== $post->getAuthor()->getId() && !$this->userService->isAdmin($userId)) {
            $errors[] = "Vous n'êtes pas autorisé à supprimer ce post !";
        }

        if (!empty($errors)) {
            return new Response($this->twig->render('singlepost.html.twig', [
                'errors' => $errors,
                'post' => $post,
            ]));
        }
        $this->postService->deletePost(intval($postId));
        $_SESSION['success'] = 'Post supprimé avec succès';

        header('Location: /posts');

        exit();
    }
}
