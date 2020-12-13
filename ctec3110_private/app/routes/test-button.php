<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

/**
 * @param Request $request
 * @param Response $response
 * @return mixed
 */
$app->get('/test-button', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'testbutton.html.twig');
});
