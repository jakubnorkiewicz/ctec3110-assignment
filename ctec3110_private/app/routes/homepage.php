<?php

use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

$app->get('/', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'homepage.html.twig');
});

$app->post('/register', function (Request $request, Response $response, $args) {
    $user = new User();
    $user->email = $request->getParsedBody()['email'];
    $user->password = password_hash($request->getParsedBody()['password'], PASSWORD_BCRYPT);
    $user->save();

    return $response->withStatus(302)->withHeader('Location', '/');
});

