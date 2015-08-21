<?php
namespace AppBundle\Communication\Email;
class ExternalProvider implements ProviderInterface
{
    private $providerHost;
    public function __construct($providerHost)
    {
        $this->providerHost = $providerHost;
    }
    public function send(Message $message)
    {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->providerHost .
            'communication/external_provider');
        $result = curl_exec($curl);




        if($result === false || curl_getinfo($curl)['http_code']!=200) {
            $result = 2;
        }

        return $result;
    }
}