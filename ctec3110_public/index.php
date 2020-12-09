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

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../ctec3110_private/config.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

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
$log->pushHandler(new StreamHandler(__DIR__ . '/../ctec3110_private/storage/log/monolog.log', Logger::WARNING));
$log->pushHandler(new Monolog\Handler\StreamHandler("php://output", Logger::WARNING));
ErrorHandler::register($log);

// Create connection with database
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => DB_DRIVER,
    'host' => DB_HOST,
    'port' => DB_PORT,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASSWORD,
]);
$capsule->bootEloquent();
$capsule->setAsGlobal();

// Create Twig
$twig = Twig::create(__DIR__ . '/../ctec3110_private/components',
    ['cache' => false]);
//    ['cache' => __DIR__ . '/../storage/cache']);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

require __DIR__ . '/../ctec3110_private/app/routes.php';

$app->run();
