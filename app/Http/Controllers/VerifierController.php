<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class VerifierController extends MonitoringController
{

    public function __construct()
    {

    }

    public function cdnjson(): JsonResponse
    {
        // Empty response. Will be filled by middleware.
        return response()->json([], 200,[],JSON_UNESCAPED_SLASHES);
    }
}
