<?php

namespace App\Http\Middleware;

use App\Services\CMSSignatureService;
use Closure;

class CMSSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $response = $next($request);

        $signature = CMSSignatureService::getSignature($response->getContent());

        $response->header("Signature", $signature);
        return $response;
    }
}
