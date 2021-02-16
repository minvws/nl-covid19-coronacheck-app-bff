<?php

namespace App\Http\Controllers;

use App\Services\OneTimeTestSignatureCheckService;
use App\Services\SessionService;
use App\Services\CtClService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HolderController extends MonitoringController
{

    public function __construct()
    {

    }

    public function cdnjson(): JsonResponse
    {
        // Empty response. Will be filled by middleware.
        return response()->json([], 200,[],JSON_UNESCAPED_SLASHES);
    }

    public function nonce(CtClService $ctclService, SessionService $sessionService): JsonResponse
    {
        try {
            $sessionToken = $sessionService->generateNewSessionToken();
            $nonce = $ctclService->getNonce();

            $sessionService->setSessionNonce($sessionToken,$nonce);

            return response()->json(["nonce" => $nonce, "stoken" => $sessionToken], 200,[],JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            Log::error('Failed to get nonce');
            return response()->json(["status" => "error", "code" => 0],500,JSON_UNESCAPED_SLASHES);
        }
    }

    public function proof(Request $request, CtClService $ctClService, SessionService $sessionService,
                          OneTimeTestSignatureCheckService $oneTimeCheckService): JsonResponse
    {
        // String
        $stoken = $request->json()->get('stoken');

        // Wrapped in string(json(payload/signature))
        $testResult = json_decode($request->json()->get('test'));

        // Json in a string
        $icm = $request->json()->get('icm');

        if(empty($stoken) || empty($testResult) || empty($icm)) {
            Log::error('Cannot create proof. Did not receive stoken, testResult, or icm.');

            return response()->json(["status" => "error", "code" => 0], 500,JSON_UNESCAPED_SLASHES);
        }
        try {

            // Test Signature on test result
            // TODO: Implement signature validation service

            // Unwrap Test Payload
            $testResultPayloadDecoded = base64_decode($testResult->payload);
            $testResultPayloadJson = json_decode($testResultPayloadDecoded);

            // Validate Test Values
            // TODO: Refactor and implement test value validation

            // Create Unix time from sampleDate
            $sampleTime = strtotime($testResultPayloadJson->result->sampleDate);

            // Test was not negative
            if($testResultPayloadJson->result->negativeResult != true) {
                $data = ["status" => "error", "code" => 99993];
                return response()->json($data,400,[],JSON_UNESCAPED_SLASHES);
            }

            // Test long ago
            // TODO: make this dynamic
            if($sampleTime < (time() - (60*60*24*2))) {
                $data = ["status" => "error", "code" => 99992];
                return response()->json($data, 400,[],JSON_UNESCAPED_SLASHES);
            }

            // Test in the future
            if($sampleTime > time()) {
                $data = ["status" => "error", "code" => 99991];
                return response()->json($data,400,[],JSON_UNESCAPED_SLASHES);
            }

            // Test was issued before
            $oneTimeCheckServiceResult = $oneTimeCheckService->signTestResult(
                $testResultPayloadJson->providerIdentifier,
                $testResultPayloadJson->result->unique
            );

            if(!$oneTimeCheckServiceResult) {
                return response()->json(["status" => "error", "code" => 99994],400,[],JSON_UNESCAPED_SLASHES);
            }

            // ICM Should be in string form, but may be string or json.
            if(!is_string($icm)) {
                $icm = json_encode($icm,JSON_UNESCAPED_SLASHES);
            }

            // Load Nonce
            $nonce = $sessionService->getSessionNonce($stoken);

            // Round to nearest hour just in case they forgot
            $sampleTime = $sampleTime - ($sampleTime%(60*60));

            // Get Proof
            $issueSignatureMessage = $ctClService->getProof(
                $nonce,
                base64_encode($icm),
                $sampleTime,
                $testResultPayloadJson->result->testType
            );

            return response()->json(
                ["ism" => $issueSignatureMessage->ism, "attributes" => $issueSignatureMessage->attributes],
                200,
                [],
                JSON_UNESCAPED_SLASHES
            );

        } catch (\Exception $e) {
            Log::error('Failed to create proof for stoken <'.$stoken.'>');
            return response()->json(
                ["status" => "error", "code" => 0],
                500,
                [],
                JSON_UNESCAPED_SLASHES
            );
        }
    }

}
