<?php

require_once __DIR__ . '/vendor/autoload.php';

use AltoRouter as AltoRouter;
use App\Controllers\HomeController;
require_once __DIR__ . '/src/Controllers/HomeController.php';

$uri = $_SERVER['REQUEST_URI'];
$router = new AltoRouter();


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
            $home = new HomeController();
            $response = $home->index();
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