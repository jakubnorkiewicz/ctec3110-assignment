<?php

use App\Models\SentMessage;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;


$app->get('/sms-form', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'sms-form.html.twig', ['clean'=>true]);
});

$app->post('/send-sms', function (Request $request, Response $response, $args) {

        $view = Twig::fromRequest($request);
        $v = new Valitron\Validator($_POST);
        $v->rule('required', ['phoneNumber', 'message']);
        $v->rule('integer', 'phoneNumber');
        $v->rule('lengthBetween', 'phoneNumber', 9, 12);
        $v->rule('lengthBetween', 'message', 1, 160);

        if($v->validate()) {

            $message = new SentMessage();
            $message->destination_number = $request->getParsedBody()['phoneNumber'];
            $message->value = $request->getParsedBody()['message'];
            $message->save();
            return $view->render($response, 'sms-form.html.twig', ['validationPassed' => true]);

        } else {
            return $view->render($response, 'sms-form.html.twig', ['validationPassed'=>false]);

        }
});