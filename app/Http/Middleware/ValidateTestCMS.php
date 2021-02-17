<?php

namespace App\Http\Middleware;

use App\Services\CMSSignatureService;
use Illuminate\Http\Request;
use Closure;
Use \Exception;

class ValidateTestCMS
{
    private CMSSignatureService $cmsSignatureService;

    public function __construct(CMSSignatureService $cmsSignatureService) {
        $this->cmsSignatureService = $cmsSignatureService;
    }

    public function handle(Request $request, Closure $next): mixed
    {

        // Check if the call contains a test result
        try {
            if ($request->header('Content-Type') != "application/json") {
                $errorCode = 99981;
                throw new Exception('Content should be json');
            }

            $json = json_decode($request->getContent());

            if (!isset($json->test) || empty($json->test)) {
                $errorCode = 99982;
                throw new Exception('Content should contain a test result');
            }

            $test = json_decode($json->test);

            if (!isset($test->payload) || empty($test->payload)
                ||
                !isset($test->signature) || empty($test->signature)
            ) {
                $errorCode = 99982;
                throw new Exception('Content should contain a payload and signature');
            }

            // Check the signature
            if (!$this->cmsSignatureService->checkThirdPartyTestSignature($test->payload, $test->signature)) {
                $errorCode = 99983;
                throw new Exception('Signature is not valid or not trusted');
            }

        } catch (Exception $exception) {
            $data = ["status" => "error", "code" => $errorCode ?? 0];
            return response()->json($data, 400);
        }

        return $next($request);
    }

}
