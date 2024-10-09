<?php

namespace MrThito\LaravelBilling;

use Illuminate\Support\ServiceProvider;
use MrThito\LaravelBilling\Commands\LaravelBillingCommand;

class LaravelBillingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishAssets();
        }
    }

    public function register()
    {
        $this->commands([
            LaravelBillingCommand::class,
        ]);
    }

    protected function publishAssets()
    {
        // Config
        $this->publishes([
            __DIR__.'/../config/billing.php' => config_path('laravel-billing.php'),
        ], 'laravel-billing-config');

        // Views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-billing'),
        ], 'laravel-billing-views');

        // Migrations
        $this->publishes([
            __DIR__.'/../database/migrations/update_users_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'update_users_table.php'),
            __DIR__.'/../database/migrations/create_subscriptions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_subscriptions_table.php'),
            __DIR__.'/../database/migrations/create_subscription_items_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_subscription_items_table.php'),
            __DIR__.'/../database/migrations/create_tax_rates_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'create_tax_rates_table.php'),
        ], 'laravel-billing-migrations');
    }
}
