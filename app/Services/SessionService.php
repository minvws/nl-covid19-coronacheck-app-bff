<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class SessionService
{
    private String $sessionToken;

    public function __construct($sessionToken = "") {
        $this->sessionToken = $sessionToken;
    }

    public function getSessionToken() : String {
        return $this->sessionToken;
    }

    public function getNonce() : String {
        return Redis::get('session:'.$this->sessionToken.':nonce');
    }

    public function registerNonce($nonce) : SessionService {
        if($this->sessionToken == "") {
            throw new \Exception("Cannot register nonce without a sessionToken");
        }

        // Register Session and Nonce in Redis
        Redis::set('session:'.$this->sessionToken.':nonce', $nonce);

        return $this;
    }

    public function generateSessionToken() : SessionService {
        $this->sessionToken = bin2hex(openssl_random_pseudo_bytes(32));
        return $this;
    }
}
