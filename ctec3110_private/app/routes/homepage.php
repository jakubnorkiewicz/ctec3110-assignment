<?php

use App\Actions\LoginSubmitAction;
use App\Actions\LogoutAction;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

/**
 * @param Request $request
 * @param Response $response
 * @return mixed
 */
$app->get('/', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'homepage.html.twig', ['clean'=>true]);
});

/**
 * @param Request $request
 * @param Response $response
 * @return Response
 */
$app->post('/register', function (Request $request, Response $response, $args) {


    $view = Twig::fromRequest($request);
    $v = new Valitron\Validator($_POST);
    $v->rule('required', ['email', 'password']);
    $v->rule('email', 'email');
    $v->rule('lengthBetween', 'password', 8, 64);

    if($v->validate()) {

        $user = new User();
        $user->email = $request->getParsedBody()['email'];
        $user->password = password_hash($request->getParsedBody()['password'], PASSWORD_BCRYPT);
        $user->save();
        return $view->render($response, 'homepage.html.twig', ['validationPassed' => true]);

    } else {
        return $view->render($response, 'homepage.html.twig', ['validationPassed' => false]);

    }
});

/**
 * @return object
 */
$app->post('/login', LoginSubmitAction::class);

/**
 * @return object
 */
$app->post('/logout', LogoutAction::class);
