<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Controllers/HomeController.php';

use AltoRouter as AltoRouter;
use App\Controllers\HomeController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$uri = $_SERVER['REQUEST_URI'];
$router = new AltoRouter();

// Initialize Twig
$loader = new FilesystemLoader('C:\xampp2\htdocs\blog_php_oc\templates');
$twig = new Environment($loader);

$twig->addFunction(new \Twig\TwigFunction('path', function ($name, $params = []) use ($router) {
    return $router->generate($name, $params);
}));


$router->map('GET', '/blog_php_oc/', 'HomeController#index', 'home');

// Match the current request
$match = $router->match();

// Debugging information
if ($match) {
    echo 'Matched route: ' . $match['name'] . '<br>';
    echo 'Params: ' . print_r($match['params'], true) . '<br>';
} else {
    echo 'No route matched for URI: ' . $uri . '<br>';
}


if ($match) {
    list($controller, $action) = explode("#", $match['target']);
    switch ($controller) {
        case "HomeController":
            $home = new HomeController($twig);
            $response = $home->$action();
            echo $response;
            break;
        default:
            echo "404 Page Not Found";
            break;
    }
} else {
    header("Location: /BlogV1/");
    exit;
}

?>
