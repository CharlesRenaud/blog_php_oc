<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class AssetsController
{
    public function serveCss(): Response
    {
        $cssContent = file_get_contents(__DIR__ . '/../../assets/styles/base.css');
        
        if ($cssContent !== false) {
            return new Response($cssContent, 200, ['Content-Type' => 'text/css']);
        } else {
            // Gérer les erreurs si le fichier CSS n'est pas trouvé ou ne peut pas être lu
            return new Response('Erreur lors de la récupération du fichier CSS.', 404);
        }
    }

    public function serveJs(): Response
    {
        $jsContent = file_get_contents(__DIR__ . '/../../assets/scripts/app.js');
        
        if ($jsContent !== false) {
            return new Response($jsContent, 200, ['Content-Type' => 'application/javascript']);
        } else {
            // Gérer les erreurs si le fichier JavaScript n'est pas trouvé ou ne peut pas être lu
            return new Response('Erreur lors de la récupération du fichier JavaScript.', 404);
        }
    }
}
