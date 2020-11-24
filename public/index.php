<?php
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();


// Create Twig
$twig = Twig::create(__DIR__ . '/../components',
    ['cache' => false]);
//    ['cache' => __DIR__ . '/../storage/cache']);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

require '../app/routes.php';

$app->run();
