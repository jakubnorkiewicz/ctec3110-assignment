<?php

//
// Add this line to your crontab file:
//
// * * * * * cd /path/to/project && php jobby.php 1>> /dev/null 2>&1
//

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        $dotenv = Dotenv::createImmutable(__DIR__ . '../../');
        $dotenv->load();

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ]);

        $capsule->bootEloquent();
        $capsule->setAsGlobal();

        require_once __DIR__ . '/../vendor/autoload.php';
        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        // Initialize WS with the WSDL
        $client = new SoapClient($_ENV['WSDL_URL'], $soap_client_parameters);

        $paramsReadMessages = array(
            "username" => $_ENV['WSDL_USERNAME'],
            "password" => $_ENV['WSDL_PASSWORD'],
            "count" => 10,
            "deviceMSISDN" => "",
        );

        $results = $client->__soapCall("peekMessages", $paramsReadMessages);

        foreach ($results as $result) {
            $json = json_encode(simplexml_load_string($result));
            $array = json_decode($json,true);

            if (in_array('iotdevice', $array)) {
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

            // Send email
                $mail = new PHPMailer(true);

                try {
                    $mail->setFrom('sms.assessment@dmu.ac.uk', 'CTEC3110 SMS Service');
                    $mail->addAddress('bzakrzewski1@gmail.com');
                    $mail->addAddress('p17215071@my365.dmu.ac.uk');
                    //TODO use emails of all registered users
                    $mail->Subject = 'Got new message';
                    $mail->Body = 'Got new message: '. $message->value;
                    $mail->send();
                }
                catch (Exception $e) {
                    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
                }
            }
        }
        return true;
    },
    'schedule' => '* * * * *',
    'output' => 'logs/closure.log',
    'enabled' => true,
));

$jobby->run();
