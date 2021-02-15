<?php

namespace App\Providers;

use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SessionService::class, function ($app) {
            return new SessionService();
        });
    }
}
