<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetsController
{
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
    }
}
