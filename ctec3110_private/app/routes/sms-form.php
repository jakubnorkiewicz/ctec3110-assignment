<?php
use Respect\Validation\Validator as v;

use App\Models\Message;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;


$app->get('/sms-form', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'sms-form.html.twig');
});


/**
 * Validation docs: https://github.com/DavidePastore/Slim-Validation
 */

//Create the validators
$numberValidator = v::numeric()->noWhitespace()->length(9, 12);
$messageValidator = v::length(1,160);
$validators = array(
    'number' => $numberValidator,
    'message' => $messageValidator
);

$app->post('/send-sms', function (Request $request, Response $response, $args) {

    if ($request->getAttribute('has_errors')) {
        $errors = $request->getAttribute('errors');
    } else {
        $message = new Message();
        $message->destination_number = $request->getParsedBody()['phoneNumber'];
        $message->value = $request->getParsedBody()['message'];
        $message->save();
        return $response->withStatus(302)->withHeader('Location', '/');
    }

})->add(new \DavidePastore\Slim\Validation\Validation($validators));