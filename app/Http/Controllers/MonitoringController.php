<?php

namespace App\Http\Controllers;

use App\Services\MonitoringService;
use Illuminate\Http\JsonResponse;
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
        $results = array();
        $results[] = ["service" => "redis", "isHealthy" => $ms->checkRedis()];

        $overallHealth = true;
        foreach($results as $r) {
            if(!$r["isHealthy"]) {
                $overallHealth = false;
                break;
            }
        }

        return response()->json(["isHealthy" => $overallHealth, "results" => $results],200);
    }
}
