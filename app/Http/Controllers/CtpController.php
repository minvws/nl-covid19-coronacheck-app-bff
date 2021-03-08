<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class CtpController extends BaseController
{

    public function __construct()
    {

    }

    public function paper(): JsonResponse
    {
        return response()->json([], 501,[],JSON_UNESCAPED_SLASHES);
    }
}
