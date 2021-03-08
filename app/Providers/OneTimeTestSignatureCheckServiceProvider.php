<?php

namespace App\Providers;

use App\Services\OneTimeTestSignatureCheckService;
use Illuminate\Support\ServiceProvider;

class OneTimeTestSignatureCheckServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(OneTimeTestSignatureCheckService::class, function ($app) {
            return new OneTimeTestSignatureCheckService();
        });
    }

}
