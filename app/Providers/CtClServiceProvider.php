<?php

namespace App\Providers;

use App\Services\CtClService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CtClServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public function register()
    {
        $this->app->singleton(CtClService::class, function ($app) {
            return new CtClService(config('app.ctcl_host'),config('app.ctcl_port'));
        });
    }

    public function provides()
    {
        return ['ctcl'];
    }

}
