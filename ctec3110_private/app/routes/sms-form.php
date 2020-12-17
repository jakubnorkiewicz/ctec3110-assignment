<?php

use App\Models\SentMessage;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

/**
 * @param Request $request
 * @param Response $response
 * @return mixed
 */

$app->get('/sms-form', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'sms-form.html.twig', [
        'clean'=>true,
        'user' => $_SESSION['_sf2_attributes']['user'] ?? null
    ]);
});

/**
 * @param Request $request
 * @param Response $response
 * @return Response
 */
$app->post('/send-sms', function (Request $request, Response $response, $args) {

        $view = Twig::fromRequest($request);
        $v = new Valitron\Validator($_POST);
        $v->rule('required', ['phoneNumber', 'message']);
        $v->rule('lengthBetween', 'phoneNumber', 9, 13);
        $v->rule('lengthBetween', 'message', 1, 160);

        if($v->validate()) {

            $message = new SentMessage();
            $message->destination_number = $request->getParsedBody()['phoneNumber'];
            $message->value = $request->getParsedBody()['message'];
            $message->save();

            // TODO move to .env
            $wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
            $soap_client_parameters = ['trace' => true, 'exceptions' => true];

            // Initialize WS with the WSDL
            $client = new SoapClient($wsdl, $soap_client_parameters);

            $paramsSendMessage = array(
                "username" => '20_1721507', // TODO move to .env
                "password" => "HU@4xt6WXdGF", // TODO move to .env
                "deviceMSISDN" => $request->getParsedBody()['phoneNumber'],
                "message" => $request->getParsedBody()['message'],
                "deliveryReport" => false,
                "mtBearer" => "SMS"
            );
            
            // Invoke with the request params
            $client->__soapCall("sendMessage", $paramsSendMessage);

            return $view->render($response, 'sms-form.html.twig', [
                'validationPassed' => true,
                'user' => $_SESSION['_sf2_attributes']['user']
            ]);

        } else {
            return $view->render($response, 'sms-form.html.twig', [
                'validationPassed'=>false,
                'user' => $_SESSION['_sf2_attributes']['user']
            ]);

        }
});