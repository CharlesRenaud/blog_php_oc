<?php

namespace App\Controllers;

use Twig\Environment;

class HomeController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        $template = $this->twig->load('home.html.twig');
        return $template->render([]);
    }
}

?>