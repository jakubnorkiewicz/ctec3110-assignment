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
    $soap_client_parameters = ['trace' => true, 'exceptions' => true];

    // Initialize WS with the WSDL
    $client = new SoapClient($_ENV['WSDL_URL'], $soap_client_parameters);

    $paramsReadMessages = array(
        "username" => $_ENV['WSDL_USERNAME'],
        "password" => $_ENV['WSDL_PASSWORD'],
        "count" => 10,
        "deviceMSISDN" => "",
    );

    // Invoke with the request params
    $result = $client->__soapCall("peekMessages", $paramsReadMessages);
    $json = json_encode(simplexml_load_string($result[1]));
    $array = json_decode($json,true);
    var_dump($array);
    var_dump($result);
    echo '<br><br><br>';
    var_dump(ReceivedMessage::all());

    return $view->render($response, 'display-messages.html.twig', [
//      'received_messages' => $result, TODO use the $result instead of the dummy data
        'received_messages' => ReceivedMessage::all(),
        'user' => $_SESSION['_sf2_attributes']['user'] ?? null
    ]);
});
