<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../ctec3110_private/config.php';

$app = AppFactory::create();

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
