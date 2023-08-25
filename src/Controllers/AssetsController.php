<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetsController
{
    public function serveCss(): Response
    {
        return new BinaryFileResponse(__DIR__ . '/../../assets/styles/base.css', 200, ['Content-Type' => 'text/css']);
    }

    public function serveJs(): Response
    {
        return new BinaryFileResponse(__DIR__ . '/../../assets/scripts/app.js', 200, ['Content-Type' => 'application/javascript']);
    }
}
