<?php

<<<<<<< HEAD
declare(strict_types=1);

namespace App\Controllers;

use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Twig\Environment;
use Symfony\Component\Translation\TranslatorInterface;
=======
namespace App\Controllers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swift_Mailer;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

class HomeController
{
    private const FROM_EMAIL = 'renaudcharlespro@gmail.com';
    private const FROM_NAME = 'Le blog OC';
    private const TO_EMAIL = 'smtpocblogtest@gmail.com';
<<<<<<< HEAD
    private const EMAIL_SUBJECT = 'Nouveau message depuis le formulaire de contact';
=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

    private Environment $twig;
    private Swift_Mailer $mailer;

    public function __construct(Environment $twig, Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function index(Request $request): Response
    {
<<<<<<< HEAD
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

        // Vérifier si les nombres aléatoires sont déjà définis
        $_SESSION['number1'] = isset($_SESSION['number1']) ? $_SESSION['number1'] : rand(0, 9);
        $_SESSION['number2'] = isset($_SESSION['number2']) ? $_SESSION['number2'] : rand(0, 9);

<<<<<<< HEAD
        $number1 = $_SESSION['number1'];
        $number2 = $_SESSION['number2'];
=======
        $number1 = $_SESSION["number1"];
        $number2 = $_SESSION["number2"];

>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

        if ($request->getMethod() === 'POST') {
            $data = [
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'message' => $request->request->get('message'),
<<<<<<< HEAD
                'maths' => $request->request->get('maths'),
            ];

            // Validate the CSRF token
            if (!$this->isCsrfTokenValid((string) $request->request->get('csrf_token'))) {
                return new Response($this->twig->render('error.html.twig', [
                    'errors' => ['csrf_token' => 'Invalid CSRF token.'],
                ]));
            }

=======
                'maths' => $request->request->get('maths')
            ];
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
            $errors = $this->validateFormData($data);

            // Validation de la réponse à la question mathématique
            if (intval($data['maths']) !== ($number1 + $number2)) {
<<<<<<< HEAD
                $errors['maths'] = 'La réponse à la question de mathématiques est incorrecte. Veuillez réessayer.';
=======
                $errors['maths'] = "La réponse à la question de mathématiques est incorrecte. Veuillez réessayer.";
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
            }

            if (count($errors) > 0) {
                return new Response($this->twig->render('error.html.twig', [
<<<<<<< HEAD
                    'errors' => $errors,
                    
=======
                    'errors' => $errors
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
                ]));
            }

            // Envoi de l'e-mail
<<<<<<< HEAD
            $message = (new \Swift_Message(self::EMAIL_SUBJECT))
=======
            $message = (new \Swift_Message('Nouveau message depuis le formulaire de contact'))
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
                ->setFrom([self::FROM_EMAIL => self::FROM_NAME])
                ->setTo([self::TO_EMAIL])
                ->setBody(
                    sprintf("Nom : %s\nEmail : %s\n\nMessage : \n%s", $data['name'], $data['email'], $data['message']),
                    'text/plain'
                );

            $result = $this->mailer->send($message);

            // Affichage d'une réponse
            if ($result) {
                $_SESSION['number1'] = rand(0, 9);
                $_SESSION['number2'] = rand(0, 9);

<<<<<<< HEAD
                return new Response($this->twig->render('success.html.twig', [
                    'message' => 'Message envoyé avec succès, redirection en cours ...',
                    'goTo' => '/',
                ]));
            }

            return new Response($this->twig->render('error.html.twig'));
        }

        // Generate a new CSRF token or retrieve the existing one
        $csrfToken = $_SESSION['csrf_token'] ?? $this->generateCsrfToken();
        $_SESSION['csrf_token'] = $csrfToken;

        // Affichage du formulaire avec les nombres aléatoires
        return new Response($this->twig->render('home.html.twig', [
            'number1' => $number1,
            'number2' => $number2,
            'csrf_token' => $csrfToken,
=======
                unset($_SESSION['number2']);

                return new Response($this->twig->render('success.html.twig', [
                    'message' => 'Message envoyé avec succès, redirection en cours ...',
                    'goTo' => '/blog_php_oc/'
                ]));
            } else {
                return new Response($this->twig->render('error.html.twig'));
            }
        }

        // Affichage du formulaire avec les nombres aléatoires
        return new Response($this->twig->render('home.html.twig', [
            'number1' => $number1,
            'number2' => $number2
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        ]));
    }

    private function validateFormData(array $data): array
    {
        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
<<<<<<< HEAD
            'name' => new Assert\NotBlank(['message' => 'Le nom ne peut pas être vide.']),
            'email' => [
                new Assert\NotBlank(['message' => 'L\'adresse e-mail ne peut pas être vide.']),
                new Assert\Email(['message' => 'L\'adresse e-mail n\'est pas valide.']),
            ],
            'message' => new Assert\NotBlank(['message' => 'Le message ne peut pas être vide.']),
            'maths' => new Assert\NotBlank(['message' => 'Le champ maths ne peut pas être vide.']),
        ]);
    
        $violations = $validator->validate($data, $constraints);
    
        $errors = [];
    
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
    
        return $errors;
    }
    

    private function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        return $token;
    }

    private function isCsrfTokenValid(?string $token): bool
    {
        return hash_equals((string) ($_SESSION['csrf_token'] ?? ''), (string) $token);
    }
=======
            'name' => new Assert\NotBlank(),
            'email' => new Assert\Email(),
            'message' => new Assert\NotBlank(),
            'maths' => new Assert\NotBlank(),
        ]);

        $violations = $validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
}
