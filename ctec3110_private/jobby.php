<?php

//
// Add this line to your crontab file:
//
// * * * * * cd /path/to/project && php jobby.php 1>> /dev/null 2>&1
//

require_once __DIR__ . '/../vendor/autoload.php';

$jobby = new \Jobby\Jobby();

$jobby->add('CommandExample', array(
    'command' => 'ls',
    'schedule' => '* * * * *',
    'output' => 'logs/command.log',
    'enabled' => true,
));

$jobby->add('SaveToDatabase', array(
    'closure' => function() {
        require_once __DIR__ . '/../vendor/autoload.php';
        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        // Initialize WS with the WSDL
        $client = new SoapClient('https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl', $soap_client_parameters);

        $paramsReadMessages = array(
            "username" => '20_1719317',
            "password" => 'Cotojestjanie723',
            "count" => 10,
            "deviceMSISDN" => "",
        );

        $results = $client->__soapCall("peekMessages", $paramsReadMessages);

        foreach ($results as $result) {
            $json = json_encode(simplexml_load_string($result));
            $array = json_decode($json,true);

            $message = new \App\Models\ReceivedMessage();
            $message->source_number = $array['sourcemsisdm'];
            $message->destination_number = $array['destinationmsisdn'];
            $message->value = $array['message']['value'];
            $message->bearer = $array['bearer'];
            $message->message_ref = $array['messageref'];
            $message->switch = $array['message']['iotdevice']['switches'];
            $message->fan_fwd_or_rvs = $array['message']['iotdevice']['fan'];
            $message->keypad_number = $array['message']['iotdevice']['keypad'];
            $message->heater_temp = $array['message']['iotdevice']['temperature'];
            $message->save();
        }
        return true;
    },
    'schedule' => '* * * * *',
    'output' => 'logs/closure.log',
    'enabled' => true,
));

$jobby->run();
