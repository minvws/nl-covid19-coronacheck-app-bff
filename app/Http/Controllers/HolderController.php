<?php

namespace App\Http\Controllers;
use App\Services\CtClService;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class HolderController extends MonitoringController
{

    public function __construct()
    {

    }

    public function config(): JsonResponse
    {
        $config = json_encode(array());
        return response()->json($config);
    }

    public function config_ctp(): JsonResponse
    {
        $config = json_encode(array());
        return response()->json($config);
    }


    public function public_keys(): JsonResponse
    {
        $config = json_encode(array());
        return response()->json($config);
    }

    public function test_types(): JsonResponse
    {
        $config = json_encode(array());
        return response()->json($config);
    }

    public function nonce(): JsonResponse
    {
        try {
            // Get Nonce from CtCl
            $ctCl = new CtClService();

            // Get Session Token
            $sessionService = new SessionService();
            $sessionService->generateSessionToken();
            $sessionService->registerNonce($ctCl->getNonce());

            $json = array();
            $json["nonce"] = $ctCl->getNonce();
            $json["stoken"] = $sessionService->getSessionToken();

            return response()->json($json, 200);
        } catch (\Exception $e) {
            return response()->json(array("status" => "error"),500);
        } finally {

        }
    }

    public function proof(): JsonResponse
    {
        $config = json_encode(array());
        return response()->json($config);
    }

}
