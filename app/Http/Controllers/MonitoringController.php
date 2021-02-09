<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class MonitoringController extends BaseController
{
    public function ping(): JsonResponse
    {
        return response()->json("pong",200);
    }

    public function status(): JsonResponse
    {
        $status = new stdClass();
        $status->redis = 1;
        $status->database = 1;
        $status->config = 1;
        $status->config_ctp = 1;
        $status->public_keys = 1;
        $status->test_types = 1;
        $status->ctcl->nonce = 1;
        $status->ctcl->proof = 1;

        return response()->json($status,200);
    }
}
