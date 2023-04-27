<?php

namespace App\Controllers;

use Doctrine\ORM\EntityManager;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entities\User;
use App\Services\UserService;

class SessionController
{

    private $entityManager;
    private $twig;
    private $userService;

    public function __construct(EntityManager $entityManager, Environment $twig, UserService $userService)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $username = $request->request->get('name');
            $password = $request->request->get('password');
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['name' => $username]);
            if ($user) {
                if (password_verify($password, $user->getPassword())) {
                    // Information correctes, connecter l'utilisateur
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['name'] = $user->getName();
                    $_SESSION['is_admin'] = $user->getIsAdmin();
                    $_SESSION['authenticated'] = true;
                    return new RedirectResponse('/blog_php_oc');
                } else {
                    // Information incorrectes, afficher un message d'erreur
                    return new Response($this->twig->render('login.html.twig', [
                        'error' => 'Nom ou mot de passe incorect',
                    ]));
                }
            } else {
                // L'utilisateur n'existe pas, afficher un message d'erreur
                return new Response($this->twig->render('login.html.twig', [
                    'error' => "L'utilisateur n'existe pas",
                ]));
            }
        } else if ($request->getMethod() === 'GET') {
            return new Response($this->twig->render('login.html.twig'));
        }
    }
    public function register(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return new Response($this->twig->render('register.html.twig'));
        } elseif ($request->getMethod() === 'POST') {
            $data = [
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'isAdmin' => false,
                'password' => $request->request->get('password')
            ];
            $userService = new UserService($this->entityManager);
            if ($userService->checkEmailExists($data['email'])) {
                return new Response("L'email est déjà utilisé", Response::HTTP_BAD_REQUEST);
            }
            $user = $userService->createUser($data);
            if ($user) {
                // Render the success template with the message and goTo variables
                return new Response($this->twig->render('success.html.twig', [
                    'message' => 'Utilisateur Enrengistré, redirection vers la page de connexion',
                    'goTo' => '/blog_php_oc/login'
                ]));
            } else {
                return new Response("Une erreur est survenue lors de l'inscription", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function logout()
    {
        session_destroy();
        return new RedirectResponse('/blog_php_oc/');
    }
    public function serveCss(Request $request)
    {
        $content = file_get_contents(__DIR__ . '/../../assets/styles/base.css');

        $response = new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/css']);
        return $response;
    }
}
