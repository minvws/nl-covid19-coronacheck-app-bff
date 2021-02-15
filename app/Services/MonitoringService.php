<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class MonitoringService
{

    public function checkRedis() : bool {
        $key = 'monitoring_check:'.uniqid();
        $dataIn = time();
        Redis::set($key, $dataIn, 'EX', 5);
        $dataOut = Redis::get($key);

        return ($dataIn == $dataOut);
    }

    public function checkServiceEnabled() : bool {
        return config('app.service_enabled');
    }

}
