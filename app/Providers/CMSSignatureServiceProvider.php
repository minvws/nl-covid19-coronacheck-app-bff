<?php

namespace App\Providers;

use App\Services\CMSSignatureService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CMSSignatureServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public function register()
    {
        $this->app->singleton(CMSSignatureService::class, function ($app) {
            return new CMSSignatureService(
                config('app.cms_sign_x509_cert'),
                config('app.cms_sign_x509_key'),
                config('app.cms_sign_x509_pass'),
                config('app.cms_sign_x509_chain')
            );
        });
    }

    public function provides()
    {
        return ['cms'];
    }

}
