<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class SessionService
{
    public function getSessionNonce($sessionToken) : String
    {
        $key = 'session:' . $sessionToken . ':nonce';
        $data = Redis::get($key);
        return strval($data);
    }

    public function setSessionNonce($sessionToken, $nonce)
    {
        $key = 'session:'.$sessionToken.':nonce';
        $data = $nonce;
        Redis::set($key, $data, 'EX', config('app.session_duration'));
    }

    public function generateNewSessionToken() : String
    {
        return bin2hex(openssl_random_pseudo_bytes(config('app.session_token_length')));
    }
}
