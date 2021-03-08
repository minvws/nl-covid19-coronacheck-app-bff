<?php

namespace App\Providers;

use App\Services\MonitoringService;
use Illuminate\Support\ServiceProvider;

class MonitoringServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(MonitoringService::class, function ($app) {
            return new MonitoringService();
        });
    }

}
