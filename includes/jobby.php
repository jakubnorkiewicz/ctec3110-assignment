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
    'closure' => function () {

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
            $array = json_decode($json, true);


            if (isset($array['message']['groupae'])) {

                $message = new \App\Models\ReceivedMessage();

                $date = strtr($array['receivedtime'], '/', '-');
                $received_time_formatted = date("Y-m-d H:i:s", strtotime($date));

                if (\App\Models\ReceivedMessage::where('received_time', '=', $received_time_formatted)->exists()) {
                    // record exists
                } else {
                    $message->source_number = $array['sourcemsisdn'];
                    $message->destination_number = $array['destinationmsisdn'];
                    $message->bearer = $array['bearer'];
                    $message->received_time = $received_time_formatted;
                    $message->message_ref = $array['messageref'];
                    $message->switch = $array['message']['groupae']['switches'];
                    $message->fan_fwd_or_rvs = $array['message']['groupae']['fan'];
                    $message->keypad_number = $array['message']['groupae']['keypad'];
                    $message->heater_temp = $array['message']['groupae']['temperature'];
                    $message->save();

                    // Send email
                    $mail = new PHPMailer(true);

                    try {
                        $mail->setFrom('sms.assessment@dmu.ac.uk', 'CTEC3110 SMS Service');
                        $mail->addAddress('p17215071@my365.dmu.ac.uk');
                        $mail->addAddress('p17182101@my365.dmu.ac.uk');
                        $mail->addAddress('p17193179@my365.dmu.ac.uk');
                        $mail->addAddress('damianklisiewicz@gmail.com');
                        $mail->addAddress('bzakrzewski1@gmail.com');
                        $mail->addAddress('kubanorkiewicz@gmail.com');
//                    $mail->addCC('ingrams@my365.dmu.ac.uk');

                        $recipients = \App\Models\User::all();
                        foreach ($recipients as $recipient) {
                            $mail->addCC($recipient->email);
                        }

                        $mail->isHTML(true);
                        $mail->Subject = 'Got new message';
                        $mail->Body = 'New message arrived!';
                        $mail->Body .= '<br>From number: ' . $message->source_number;
                        $mail->Body .= '<br>To number: ' . $message->destination_number;
                        $mail->Body .= '<br> bearer: ' . $message->bearer;
                        $mail->Body .= '<br> message_ref: ' . $message->message_ref;
                        $mail->Body .= '<br> switch: ' . $message->switch;
                        $mail->Body .= '<br> fan_fwd_or_rvs: ' . $message->fan_fwd_or_rvs;
                        $mail->Body .= '<br> keypad_number: ' . $message->keypad_number;
                        $mail->Body .= '<br> heater_temp: ' . $message->heater_temp;
                        $mail->Body .= '<br> ------- end of message-------';

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Error: {$mail->ErrorInfo}";
                    }
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
