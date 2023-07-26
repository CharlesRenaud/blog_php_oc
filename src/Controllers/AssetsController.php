<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class AssetsController
{
    public function serveCss(): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/styles/base.css');

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/css']);
    }

    public function serveJs(): Response
    {
        $content = file_get_contents(__DIR__ . '/../../assets/scripts/app.js');

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/javascript']);
    }
}
