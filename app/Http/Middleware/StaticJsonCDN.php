<?php

namespace App\Http\Middleware;

use Closure;
use League\Flysystem\Adapter\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class StaticJsonCDN
{

    public function handle(Request $request, Closure $next, String $filename): mixed
    {
        $response = $next($request);

        try {
            $response->setContent(Storage::disk('cdnfiles')->get($filename));
            $lastModifiedTime = Storage::disk('cdnfiles')->lastModified($filename);

            return $response
                ->header('Content-Type','application/json')
                ->header('Cache-Control','public, max-age=300, s-maxage=300')
                ->header('Last-Modified',gmdate('D, d M Y H:i:s ', $lastModifiedTime) . 'GMT');
                ;
        } catch (FileNotFoundException $e) {

        }
    }

}
