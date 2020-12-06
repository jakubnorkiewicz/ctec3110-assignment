<?php
use App\Models\Message;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;


$app->get('/sms-form', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'sms-form.html.twig');
});

$app->post('/send-sms', function (Request $request, Response $response, $args) {
    $message = new Message();
    $message->destination_number = $request->getParsedBody()['phoneNumber'];
    $message->value = $request->getParsedBody()['message'];
    $message->save();
    return $response->withStatus(302)->withHeader('Location', '/');
});