<?php

namespace MrThito\LaravelBilling;

use Illuminate\Support\ServiceProvider;
use MrThito\LaravelBilling\Commands\LaravelBillingCommand;

class LaravelBillingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-billing');
    }

    public function register()
    {
        $this->commands([
            LaravelBillingCommand::class,
        ]);
    }
}
