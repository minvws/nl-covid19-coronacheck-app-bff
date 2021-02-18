<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class OneTimeTestSignatureCheckService
{
    public function canTestResultBeSigned($providerIdentifier, $unique) : bool
    {
        if(strlen($providerIdentifier) != 3 || strlen($unique) < 14) {
            return false;
        }

        // This token can always get another signed test result
        // BRB-TESTFLIGHT-Z2
        if(config('app.env' != "production")
            && $providerIdentifier == 'BRB'
            && $unique = 'd3a368c584b02296974f69f368f04ba23a9e0149'
        ) {
            return true;
        }

        $key = 'sign_test:' . hash_hmac('sha256',$providerIdentifier . ':'.$unique, "bananenbrood");
        $data = Redis::GET($key);

        return empty($data);
    }

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
