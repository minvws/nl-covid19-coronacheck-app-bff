<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaticJsonCDN
{

    public function handle(Request $request, Closure $next, String $filename): mixed
    {
        $response = $next($request);

        $response->setContent(Storage::disk('cdnfiles')->get($filename));

        return $response
            ->header('Content-Type','application/json')
            ->header('Cache-Control','max-age=300')
        ;
    }

}
