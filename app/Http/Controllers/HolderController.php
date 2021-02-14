<?php

namespace App\Http\Controllers;

use App\Services\SessionService;
use Log;
use App\Services\CtClService;
use App\Services\MonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function nonce(CtClService $ctclService, SessionService $sessionService): JsonResponse
    {
        try {
            $sessionToken = $sessionService->generateNewSessionToken();
            $nonce = $ctclService->getNonce();

            $sessionService->setSessionNonce($sessionToken,$nonce);

            return response()->json(["nonce" => $nonce, "stoken" => $sessionToken], 200);
        } catch (\Exception $e) {
            Log::error('Failed to get nonce');
            return response()->json(["status" => "error", "code" => 0],500);
        }
    }

    public function proof(Request $request, CtClService $ctClService, MonitoringService $sessionService): JsonResponse
    {
        // String
        $stoken = $request->json()->get('stoken');

        // Wrapped in string(json(payload/signature))
        $testResult = json_decode($request->json()->get('test'));

        // Json in a string
        $icm = $request->json()->get('icm');

        if(empty($stoken) || empty($testResult) || empty($icm)) {
            Log::error('Cannot create proof. Did not receive stoken, testResult, or icm.');

            return response()->json(["status" => "error", "code" => 0], 500);
        }
        try {

            // Test Signature on test result
            // TODO: Implement signature validation

            // Unwrap Test Payload
            $testResultPayloadDecoded = base64_decode($testResult->payload);
            $testResultPayloadJson = json_decode($testResultPayloadDecoded);

            // Validate Test Values
            // TODO: Implement test value validation

            // ICM Should be in string form, but may be string or json.
            if(!is_string($icm)) {
                $icm = json_encode($icm,JSON_UNESCAPED_SLASHES);
            }

            // Load Nonce
            $nonce = $sessionService->getSessionNonce($stoken);

            // Create unix time from sampleDate
            $testTime = strtotime($testResultPayloadJson->result->sampleDate);

            // Round to nearest hour just in case they forgot
            $testTime = $testTime - ($testTime%(60*60));

            // Get Proof
            $issueSignatureMessage = $ctClService->getProof(
                $nonce,
                base64_encode($icm),
                $testTime,
                $testResultPayloadJson->result->testType
            );

            // Define attributes signed in proof
            $attributes = [$testResultPayloadJson->result->testType, $testTime];

            return response()->json(["ism" => $issueSignatureMessage->ism, "attributes" => $attributes],200);
        } catch (\Exception $e) {
            Log::error('Failed to create proof for stoken <'.$stoken.'>');
            return response()->json(["status" => "error", "code" => 0],500);
        }
    }

}
