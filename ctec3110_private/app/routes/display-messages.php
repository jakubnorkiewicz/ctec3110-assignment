<?php

use App\Models\ReceivedMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

/**
 * @param Request $request
 * @param Response $response
 * @return mixed
 */
$app->get('/display', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'display-messages.html.twig', [
        'received_messages' => ReceivedMessage::all(),
    ]);
});
