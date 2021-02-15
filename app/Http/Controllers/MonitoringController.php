<?php

namespace App\Http\Controllers;

use App\Services\MonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use Laravel\Lumen\Routing\Controller as BaseController;

class MonitoringController extends BaseController
{
    public function __construct()
    {

    }

    public function ping(): JsonResponse
    {
        return response()->json("pong",200);
    }

    public function time(): JsonResponse
    {
        return response()->json(time(),200);
    }


    public function status(MonitoringService $ms): JsonResponse
    {
        $status = new \stdClass();
        $status->serviceEnabled = $ms->checkServiceEnabled();
        $status->redis = $ms->checkRedis();

        return response()->json($status,200);
    }
}
