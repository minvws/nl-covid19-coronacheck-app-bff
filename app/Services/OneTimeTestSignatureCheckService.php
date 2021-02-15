<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class OneTimeTestSignatureCheckService
{
    public function signTestResult($providerIdentifier, $unique) : bool
    {

        if(strlen($providerIdentifier) != 3 || strlen($unique) < 14) {
            return false;
        }

        $key = 'sign_test:' . hash_hmac('sha256',$providerIdentifier . ':'.$unique, "bananenbrood");
        $data = Redis::INCR($key);
        Redis::EXPIRE($key, config('app.signed_test_hash_duration'));

        return ($data == 1);
    }

}
