<?php

use App\Middleware\UserAuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

$app->get('/account', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'account.html.twig', [
        'user' => $_SESSION['_sf2_attributes']['user']
    ]);
})->add(UserAuthMiddleware::class);
