<?php

use App\Middleware\SessionMiddleware;
use DI\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->setBasePath("/p17215071");

$container->set('Session', function () use ($container) {
    $settings = $container->get('settings')['session'];
    return new Session(new NativeSessionStorage($settings));
});

$container->set('SessionInterface', function () use ($container) {
    return $container->get(Session::class);
});

// Start the session
$app->add(SessionMiddleware::class);

// Create Monolog log file
$log = new Logger('Dev');
$log->pushHandler(new StreamHandler(__DIR__ . '/../includes/storage/log/monolog.log', Logger::WARNING));
$log->pushHandler(new Monolog\Handler\StreamHandler("php://output", Logger::WARNING));
ErrorHandler::register($log);

// Create connection with database
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
]);
$capsule->bootEloquent();
$capsule->setAsGlobal();

// Create Twig
$twig = Twig::create(__DIR__ . '/../includes/components',
    ['cache' => false]);
//    ['cache' => __DIR__ . '/../storage/cache']);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

require __DIR__ . '/../includes/app/routes.php';

$app->run();
