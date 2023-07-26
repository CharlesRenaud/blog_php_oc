<?php

<<<<<<< HEAD
declare(strict_types=1);

namespace App\Controllers;

=======
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
use Symfony\Component\HttpFoundation\Response;

class AssetsController
{
<<<<<<< HEAD
    public function serveCss(): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/styles/base.css');

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/css']);
    }

    public function serveJs(): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/scripts/app.js');

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/javascript']);
=======
    public function serveCss(Request $request): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/styles/base.css');

        $response = new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/css']);
        return $response;
    }

    public function serveJs(Request $request): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/scripts/app.js');

        $response = new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/javascript']);
        return $response;
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    }
}
