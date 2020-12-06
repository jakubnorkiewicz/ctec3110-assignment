<?php

namespace App\Services;

class SoapWrapper
{
    public function __construct() {}

    public function __destruct() {}

    /**
     * @return bool|\SoapClient|string
     */
    public function createSoapClient()
    {
        $soap_client_handle = false;
        $soap_client_parameters = array();
        $exception = '';
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
        } catch (\SoapFault $exception) {
            $soap_client_handle = 'createSoapClient : Ooops - something went wrong connecting to the data supplier.  Please try again later <br/>';
        }
        return $soap_client_handle;
    }
}