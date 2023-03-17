<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Controllers/PostController.php';
require_once __DIR__ . '/src/Controllers/SessionController.php';
require_once __DIR__ . '/src/Services/PostService.php';
require_once __DIR__ . '/src/Services/CommentService.php';
require_once __DIR__ . '/src/Services/UserService.php';
require_once __DIR__ . '/src/Doctrine.php';

use AltoRouter as AltoRouter;
use Symfony\Component\HttpFoundation\Request;

use App\Controllers\HomeController;
use App\Controllers\PostController; // Ajoutez le namespace du contrôleur PostController
use App\Controllers\SessionController; // Ajoutez le namespace du contrôleur PostController
use App\Services\CommentService;
use App\Services\PostService;
use App\Services\UserService;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername('smtpocblogtest@gmail.com')
    ->setPassword('hygxjupuqdkadjhv');
$mailer = new Swift_Mailer($transport);



$uri = $_SERVER['REQUEST_URI'];
$router = new AltoRouter();

// Vérifiez si l'utilisateur est connecté et est un admin
var_dump($_SESSION);
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    $user = ['is_admin' => true, 'authenticated' => true];
} elseif (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    $user = ['is_admin' => false, 'authenticated' => true];
} else {
    $user = ['is_admin' => false, 'authenticated' => false];
}

// Initialiser twig
$loader = new FilesystemLoader('C:\xampp2\htdocs\blog_php_oc\templates');

$twig = new Environment($loader);

$twig->addFunction(new \Twig\TwigFunction('path', function ($name, $params = []) use ($router) {
    return $router->generate($name, $params);
}));

// Ajoutez la variable user à tous les templates qui étendent bas.html.twig
$twig->addGlobal('user', $user);



// Initaliser les services
$entityManager = Doctrine::getEntityManager();
$userService = new UserService($entityManager);
$commentService = new CommentService($entityManager);
$postService = new PostService($entityManager, $commentService);

// Les routes
$router->map('GET', '/blog_php_oc/', 'HomeController#index', 'home');
$router->map('POST', '/blog_php_oc/', 'HomeController#index', 'home_register');

$router->map('GET', '/blog_php_oc/posts', 'PostController#AllPosts', 'posts');
$router->map('GET', '/blog_php_oc/post/[i:id]', 'PostController#Post', 'post_single');
$router->map('POST', '/BlogV1/post/add-comment/[i:id]', 'PostController#addComment', 'add_comment');

$router->map('GET', '/blog_php_oc/login', 'SessionController#login', 'session_login_form');
$router->map('POST', '/blog_php_oc/login', 'SessionController#login', 'session_login');

$router->map('GET', '/blog_php_oc/register', 'SessionController#register', 'session_register_form');
$router->map('POST', '/blog_php_oc/register', 'SessionController#register', 'session_register');

$router->map('GET', '/blog_php_oc/logout', 'SessionController#logout', 'session_logout');


// Match the current request
$match = $router->match();

// Debugging information
if ($match) {
    echo 'Matched route: ' . $match['name'] . '<br>';
    echo 'Params: ' . print_r($match['params'], true) . '<br>';
} else {
    echo 'Pas de routes match: ' . $uri . '<br>';
}


// Match des routes
if ($match) {
    list($controller, $action) = explode("#", $match['target']);
    switch ($controller) {
        case "HomeController":
            $request = Request::createFromGlobals();
            $home = new HomeController($twig, $mailer);
            $response = $home->$action($request);
            echo $response->getContent();
            break;
        case "PostController":
            $post = new PostController($twig, $postService, $commentService, $userService);
            $request = Request::createFromGlobals();
            if ($action === 'AllPosts') {
                $response = $post->$action();
            } else if ($action === 'Post') {
                $postId = $match['params']['id'];
                $response = $post->$action($postId);
            } else if ($action === 'addComment') {
                $postId = $match['params']['id'];
                $response = $post->$action($postId);
            } else {
                echo "404 Page Not Found";
                break;
            }
            echo $response->getContent();
            break;
        case "SessionController":
            $session = new SessionController($entityManager, $twig, $userService);
            $request = Request::createFromGlobals();
            switch ($action) {
                case 'login':
                    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
                        header("Location: /blog_php_oc");
                        exit;
                    } else {
                        $response = $session->login($request);
                        echo $response->getContent();
                        break;
                    }
                case 'register':
                    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
                        header("Location: /blog_php_oc");
                        exit;
                    } else {
                        $response = $session->register($request);
                        echo $response->getContent();
                        break;
                    }
                case 'logout':
                    $session->logout();
                    break;
                default:
                    echo "404 Page Not Found";
                    break;
            }
            break;

        default:
            echo "404 Page Not Found";
            break;
    }
} else {
    header("Location: /blog_php_oc/");
    exit;
}
