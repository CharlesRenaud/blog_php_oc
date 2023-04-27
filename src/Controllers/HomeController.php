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

        // Vérifier si les nombres aléatoires sont déjà définis
        $_SESSION['number1'] = isset($_SESSION['number1']) ? $_SESSION['number1'] : rand(0, 9);
        $_SESSION['number2'] = isset($_SESSION['number2']) ? $_SESSION['number2'] : rand(0, 9);
    
        $number1 = $_SESSION["number1"];
        $number2 = $_SESSION["number2"];
    

        if ($request->getMethod() === 'POST') {
            $data = [
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'message' => $request->request->get('message'),
                'maths' => $request->request->get('maths')
            ];
            $errors = $this->validateFormData($data);

            // Validation de la réponse à la question mathématique
            var_dump($number1);
            var_dump($number2);
            var_dump($number1 + $number2);
            if (intval($data['maths']) !== ($number1 + $number2)) {
                $errors['maths'] = "La réponse à la question de mathématiques est incorrecte. Veuillez réessayer.";
            }

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
                // Render the success template with the message and goTo variables
                $_SESSION['number1'] = rand(0, 9);
                $_SESSION['number2'] = rand(0, 9);

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
        ]));
    }


    private function validateFormData(array $data)
    {
        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
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
}
