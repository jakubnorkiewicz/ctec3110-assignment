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


    // *** Duplicated from sms-form.php ***
    // TODO move to .env
    $wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
    $soap_client_parameters = ['trace' => true, 'exceptions' => true];

    // Initialize WS with the WSDL
    $client = new SoapClient($wsdl, $soap_client_parameters);

    $paramsReadMessages = array(
        "username" => '20_1721507', // TODO move to .env
        "password" => "HU@4xt6WXdGF", // TODO move to .env
        "count" => 10,
        "deviceMSISDN" => "",
    );

    // Invoke with the request params
    $result = $client->__soapCall("peekMessages", $paramsReadMessages);
    var_dump($result);
    echo '<br><br><br>';
    var_dump(ReceivedMessage::all());

    return $view->render($response, 'display-messages.html.twig', [
//      'received_messages' => $result, TODO use the $result instead of the dummy data
        'received_messages' => ReceivedMessage::all(),
        'user' => $_SESSION['_sf2_attributes']['user']
    ]);
});
