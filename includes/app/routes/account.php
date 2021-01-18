<?php

use App\Middleware\MonologMiddleware;
use App\Middleware\UserAuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;

/**
 * @param Request $request
 * @param Response $response
 * @return mixed
 */


$app->group('/p17215071', function (RouteCollectorProxy $app) {
    $app->get('/account', function (Request $request, Response $response, $args) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'account.html.twig', [
            'user' => $_SESSION['_sf2_attributes']['user']
        ]);
    })->add(UserAuthMiddleware::class);

})->add(MonologMiddleware::class);
