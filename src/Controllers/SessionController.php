<?php

<<<<<<< HEAD
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
=======
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

    private EntityManager $entityManager;
    private Environment $twig;
    private UserService $userService;

    public function __construct(EntityManager $entityManager, Environment $twig, UserService $userService)
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->entityManager = $entityManager;
        $this->twig = $twig;
<<<<<<< HEAD
=======
        $this->userService = $userService;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    }

    public function login(Request $request): Response|RedirectResponse
    {
        if ($request->getMethod() === 'POST') {
            $username = $request->request->get('name');
            $password = $request->request->get('password');
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['name' => $username]);
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
            if ($user) {
                if (password_verify($password, $user->getPassword())) {
                    // Information correctes, connecter l'utilisateur
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['name'] = $user->getName();
                    $_SESSION['is_admin'] = $user->getIsAdmin();
                    $_SESSION['authenticated'] = true;
<<<<<<< HEAD

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
=======
                    return new RedirectResponse('/blog_php_oc');
                } else {
                    // Information incorrectes, afficher un message d'erreur
                    return new Response($this->twig->render('login.html.twig', [
                        'error' => 'Nom ou mot de passe incorrect',
                    ]));
                }
            } else {
                // L'utilisateur n'existe pas, afficher un message d'erreur
                return new Response($this->twig->render('login.html.twig', [
                    'error' => "L'utilisateur n'existe pas",
                ]));
            }
        } else if ($request->getMethod() === 'GET') {
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
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
<<<<<<< HEAD
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
=======
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
                    'message' => 'Utilisateur Enregistré, redirection vers la page de connexion',
                    'goTo' => '/blog_php_oc/login'
                ]));
            } else {
                return new Response("Une erreur est survenue lors de l'inscription", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    public function logout(): RedirectResponse
    {
        session_destroy();
<<<<<<< HEAD
        header('Location: /');
        exit();
=======
        return new RedirectResponse('/blog_php_oc/');
    }

    public function serveCss(Request $request): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/styles/base.css');

        $response = new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/css']);
        return $response;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    }
}
