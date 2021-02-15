<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CtClService
{
    private String $host;
    private int $port;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    private function getBaseUrl() : String
    {
        return 'https://'.$this->host.':'.$this->port;
    }

    public function getNonce() : String
    {
        $client = new Client();
        $response = $client->post(
            $this->getBaseUrl().'/proof/nonce',
            [
                'verify' => (config('app.env') == 'Production'), // https certificate check
                RequestOptions::JSON =>
                    [
                        "SessionToken" => "xxx",
                    ]
            ]
        );

        if($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody());
            return $data->nonce;
        }
        else {
            throw new \Exception('Cannot reach CtCl Api or Incorrect Data Request');
        }
    }


    public function getProof(String $nonce, String $icm, int $testTime, String $testType) : \stdClass
    {
        $client = new Client();

        $response = $client->post(
            $this->getBaseUrl().'/proof/issue/',
            [
                'verify' => (config('app.env') == 'Production'), // https certificate check
                RequestOptions::JSON =>
                    [
                        "nonce" => $nonce,
                        "testType" => $testType,
                        "testTime" => strval($testTime),
                        "commitments" => $icm,
                        "sessionToken" => "xxx"
                    ]
            ]
        );




        if($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }
        else {
            throw new \Exception('Cannot reach CtCl Api or Incorrect Data Request');
        }
    }

}
