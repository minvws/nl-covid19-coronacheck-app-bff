<?php

namespace App\Services;


class CtClService
{
    private $host;
    private $port;

    private $nonce;

    public function __construct()
    {
        $this->host = config('app.ctcl_host');
        $this->port = config('app.ctcl_port');
        $this->nonce = null;
    }

    private function performRequest($requestType) {
        if($requestType == "nonce") {
            $url = 'https://'.$this->host.':'.$this->port.'/proof/nonce';
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url,['verify' => false]);

        if($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }
        else {
            throw new \Exception("Cannot reach CtCl Api or Incorrect Data Request");
        }
    }

    public function getNonce() {
        if($this->nonce == null) {
            $apiResult = $this->performRequest("nonce");
            $this->nonce = $apiResult->nonce;
        }
        return $this->nonce;
    }

}
