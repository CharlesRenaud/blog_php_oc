<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swift_Mailer;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


class HomeController
{
    private const FROM_EMAIL = 'renaudcharlespro@gmail.com';
    private const FROM_NAME = 'Le blog OC';
    private const TO_EMAIL = 'smtpocblogtest@gmail.com';

    private $twig;
    private $mailer;

    public function __construct(Environment $twig, Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function index(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            // Validation des données soumises
            $data = [
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'message' => $request->request->get('message')
            ];
            $errors = $this->validateFormData($data);
            if (count($errors) > 0) {
                return new Response($this->twig->render('error.html.twig', [
                    'errors' => $errors
                ]));
            }
    
            // Envoi de l'e-mail
            $message = (new \Swift_Message('Nouveau message depuis le formulaire de contact'))
                ->setFrom([self::FROM_EMAIL => self::FROM_NAME])
                ->setTo([self::TO_EMAIL])
                ->setBody(
                    sprintf("Nom : %s\nEmail : %s\n\nMessage : \n%s", $data['name'], $data['email'], $data['message']),
                    'text/plain'
                );
    
            $result = $this->mailer->send($message);
    
            // Affichage d'une réponse
            if ($result) {
                return new Response($this->twig->render('success.html.twig'));
            } else {
                return new Response($this->twig->render('error.html.twig'));
            }
        }
    
        // Affichage du formulaire
        return new Response($this->twig->render('home.html.twig'));
    }
    

    private function validateFormData(array $data)
    {
        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
            'name' => new Assert\NotBlank(),
            'email' => new Assert\Email(),
            'message' => new Assert\NotBlank(),
        ]);

        $violations = $validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
