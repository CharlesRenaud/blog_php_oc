<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/Controllers/HomeController.php';

require_once __DIR__ . '/src/Controllers/PostController.php';

require_once __DIR__ . '/src/Controllers/SessionController.php';

require_once __DIR__ . '/src/Controllers/AssetsController.php';

require_once __DIR__ . '/src/Services/PostService.php';

require_once __DIR__ . '/src/Services/CommentService.php';

require_once __DIR__ . '/src/Services/UserService.php';

require_once __DIR__ . '/src/Doctrine.php';

use AltoRouter as AltoRouter;
use App\Controllers\AssetsController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\SessionController;
use App\Services\CommentService;
use App\Services\PostService;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/*Codacy test */

session_start();

$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername('smtpocblogtest@gmail.com')
    ->setPassword('chizwfuhtvjfwktj');
$transport->setStreamOptions([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
]);
$mailer = new Swift_Mailer($transport);

$uri = $_SERVER['REQUEST_URI'];
$router = new AltoRouter();

// Vérifiez si l'utilisateur est connecté et est un admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    $user = ['is_admin' => true, 'authenticated' => true, 'name' => $_SESSION['name']];
} elseif (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    $user = ['is_admin' => false, 'authenticated' => true, 'name' => $_SESSION['name']];
} else {
    $user = ['is_admin' => false, 'authenticated' => false];
}

// Assurez-vous que toutes les variables de session sont initialisées
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
$authenticated = isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : false;
$name = isset($_SESSION['name']) ? $_SESSION['name'] : null;

// Vérifiez si l'utilisateur est connecté et est un admin
if ($is_admin === true) {
    $user = ['is_admin' => true, 'authenticated' => true, 'name' => $name];
} elseif ($authenticated === true) {
    $user = ['is_admin' => false, 'authenticated' => true, 'name' => $name];
} else {
    $user = ['is_admin' => false, 'authenticated' => false];
}

// Initialiser twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
// Créer une instance de la classe Request
$request = Request::createFromGlobals();

$twig = new Environment($loader, [
    'locale' => 'fr_FR',
    'debug' => true,
    'cache' => false,
    'globals' => [
        'app' => [
            'request' => $request,
        ],
    ],
]);

// Ajouter la variable app.request.uri à tous les templates Twig
$twig->addGlobal('app', [
    'request' => $request,
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());

$twig->addExtension(new \Twig\Extra\Intl\IntlExtension());
setlocale(LC_TIME, 'fr_FR.UTF-8');

$twig->addFunction(new \Twig\TwigFunction('path', function ($name, $params = []) use ($router) {
    return $router->generate($name, $params);
}));

// Ajoutez la variable user à tous les templates qui étendent bas.html.twig
$twig->addGlobal('user', $user);

// Initaliser les services
$entityManager = Doctrine::getEntityManager();
$userService = new UserService($entityManager);
$commentService = new CommentService($entityManager);
$postService = new PostService($entityManager, $commentService, $userService);

// Les routes
$router->map('GET', '/', 'HomeController#index', 'home');
$router->map('POST', '/', 'HomeController#index', 'home_register');

$router->map('GET', '/posts', 'PostController#AllPosts', 'posts');
$router->map('GET', '/post/[i:id]', 'PostController#Post', 'post_single');
$router->map('POST', '/post/add-comment/[i:id]', 'PostController#addComment', 'add_comment');

$router->map('GET', '/post/add-post', 'PostController#addPost', 'add_post_form');
$router->map('POST', '/post/add-post', 'PostController#addPost', 'add_post');
$router->map('POST', '/post/delete-post/[i:id]', 'PostController#deletePost', 'delete_post');

$router->map('GET', '/post/edit-post/[i:id]', 'PostController#editPost', 'edit_post_form');
$router->map('POST', '/post/edit-post/[i:id]', 'PostController#editPost', 'edit_post');

$router->map('GET', '/login', 'SessionController#login', 'session_login_form');
$router->map('POST', '/login', 'SessionController#login', 'session_login');

$router->map('GET', '/register', 'SessionController#register', 'session_register_form');
$router->map('POST', '/register', 'SessionController#register', 'session_register');

$router->map('GET', '/logout', 'SessionController#logout', 'session_logout');

$router->map('POST', '/comment/[i:id]/validate', 'PostController#validateComment', 'validate_comment');
$router->map('POST', '/comment/[i:id]/delete', 'PostController#deleteComment', 'delete_comment');

$router->map('GET', '/assets/styles/base.css', 'AssetsController#serveCss', 'serve_css');
$router->map('GET', '/assets/scripts/js.js', 'AssetsController#serveJs', 'serve_js');

// Match the current request
$match = $router->match();

// Debugging information
/*
if ($match) {
    if ($match['name'] !== "serve_css" && $match['name'] !== "serve_js") {
        echo 'Matched route: ' . $match['name'] . '<br>';
        echo 'Params: ' . print_r($match['params'], true) . '<br>';
    }
} else if ($match['name'] !== "serve_css" && $match['name'] !== "serve_js") {
    echo 'No matching routes found for: ' . $uri . '<br>';
}*/

// Match des routes
if ($match) {
    list($controller, $action) = explode('#', $match['target']);

    switch ($controller) {
        case 'HomeController':
            $request = Request::createFromGlobals();
            $home = new HomeController($twig, $mailer);
            $response = $home->$action($request);
            echo $response->getContent();

            break;
        case 'AssetsController':
            $assets = new AssetsController();
            $request = Request::createFromGlobals();

            switch ($action) {
                case 'serveJs':
                    $response = $assets->$action($request);
                    echo $response->getContent();

                    break;
                case 'serveCss':
                    $response = $assets->$action($request);
                    echo $response->getContent();

                    break;
            }

            break;
        case 'PostController':
            $post = new PostController($twig, $postService, $commentService, $userService);
            $request = Request::createFromGlobals();

            switch ($action) {
                case 'AllPosts':
                    $response = $post->$action();
                    echo $response->getContent();

                    break;
                case 'Post':
                    $postId = $match['params']['id'];
                    $response = $post->$action($postId);
                    echo $response->getContent();

                    break;
                case 'addPost':
                    $response = $post->addPost($request);
                    echo $response->getContent();

                    break;
                case 'deletePost':
                    $response = $post->deletePost($match);
                    echo $response->getContent();

                    break;
                case 'editPost':
                    $response = $post->editPost($request, $match);
                    echo $response->getContent();

                    break;
                case 'addComment':
                    $response = $post->addComment($request, $match);
                    echo $response->getContent();

                    break;
                case 'validateComment':
                    $response = $post->validateComment($request, $match);
                    echo $response->getContent();

                    break;
                case 'deleteComment':
                    $response = $post->deleteComment($request, $match);
                    echo $response->getContent();

                    break;
            }

            break;
        case 'SessionController':
            $session = new SessionController($entityManager, $twig);
            $request = Request::createFromGlobals();

            switch ($action) {
                case 'login':
                    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
                        header('Location: ');

                        exit;
                    }
                    $response = $session->login($request);
                    echo $response->getContent();

                    break;

                case 'register':
                    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
                        header('Location: ');

                        exit;
                    }
                    $response = $session->register($request);
                    echo $response->getContent();

                    break;

                case 'logout':
                    $response = $session->logout();
                    echo $response->getContent();

                    break;
                default:
                    echo '404 Page Not Found';

                    break;
            }

            break;
        default:
            echo '404 Page Not Found';

            break;
    }
} else {
    header('Location: /');

    exit;
}
