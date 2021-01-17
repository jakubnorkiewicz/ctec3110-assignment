<?php

use App\Middleware\UserAuthMiddleware;
use App\Models\ReceivedMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @param Request $request
 * @param Response $response
 * @return mixed
 */
$app->get('/display', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'display-messages.html.twig', [
        'received_messages' => ReceivedMessage::all(),
        'user' => $_SESSION['_sf2_attributes']['user'] ?? null
    ]);
})->add(UserAuthMiddleware::class);
