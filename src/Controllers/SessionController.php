<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\User;
use App\Services\UserService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class SessionController
{
    private EntityManager $entityManager;

    private Environment $twig;


    public function __construct(EntityManager $entityManager, Environment $twig)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    public function login(Request $request): Response|RedirectResponse
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

                    header('Location: /');
                    exit();
                }
                // Information incorrectes, afficher un message d'erreur
                return new Response($this->twig->render('login.html.twig', [
                    'error' => 'Nom ou mot de passe incorrect',
                ]));
            }
            // L'utilisateur n'existe pas, afficher un message d'erreur
            return new Response($this->twig->render('login.html.twig', [
                'error' => "L'utilisateur n'existe pas",
            ]));
        } elseif ($request->getMethod() === 'GET') {
            return new Response($this->twig->render('login.html.twig'));
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'GET') {
            return new Response($this->twig->render('register.html.twig'));
        } elseif ($request->getMethod() === 'POST') {
            $data = [
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'isAdmin' => false,
                'password' => $request->request->get('password'),
            ];
            $userService = new UserService($this->entityManager);

            if ($userService->checkEmailExists($data['email'])) {
                $errors = ["l'email est déjà utilisé"];
                return new Response($this->twig->render('error.html.twig', [
                    'errors' => $errors,
                ]));
            }
            $user = $userService->createUser($data);

            if ($user) {
                // Automatically log in the user after successful registration
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['name'] = $user->getName();
                $_SESSION['is_admin'] = $user->getIsAdmin();
                $_SESSION['authenticated'] = true;
    
                // Redirection to home after successful registration
                header('Location: /');
                exit();
            }

            return new Response("Une erreur est survenue lors de l'inscription", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    public function logout(): RedirectResponse
    {
        session_destroy();
        header('Location: /');
        exit();
    }
}
