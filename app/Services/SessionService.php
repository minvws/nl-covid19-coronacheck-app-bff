<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use \Exception;

class SessionService
{

    public function getSessionNonce($sessionToken) : String
    {
        $key = 'session:' . hash_hmac('sha256',$sessionToken, "bananenbrood") . ':nonce';
        $data = Redis::get($key);

        if(empty($data)) {
            throw new Exception('Cannot find nonce in redis database');
        }

        return strval($data);
    }

    public function setSessionNonce($sessionToken, $nonce)
    {
        $key = 'session:' . hash_hmac('sha256',$sessionToken, "bananenbrood") . ':nonce';
        $data = $nonce;
        Redis::set($key, $data, 'EX', config('app.session_duration'));
    }

    public function generateNewSessionToken() : String
    {
        return bin2hex(openssl_random_pseudo_bytes(config('app.session_token_length')));
    }
}
