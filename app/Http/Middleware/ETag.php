<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ETag
{

    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        $data = trim($response->getContent());
        $etag = md5($data);

        // ETag matches, no need to return data.
        if($request->header('If-None-Match') == $etag) {
            return response()->json([], 304);
        }
        else {
            return $response->header('Etag', $etag);
        }
    }

}
