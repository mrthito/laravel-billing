<?php

namespace MrThito\LaravelBilling;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MrThito\LaravelBilling\Commands\LaravelBillingCommand;
use MrThito\LaravelBilling\Livewire\Billing;

class LaravelBillingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishAssets();
        }

        // Register the package's routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/billing-routes.php');

        // Register the package's views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-billing');

        // Livewire Components for Billing Page
        Livewire::component('billing-portal', Billing::class);

        // Webhook URL should not check CSRF token
        $this->app->resolving(VerifyCsrfToken::class, function ($middleware) {
            $middleware->except('laravel-billing/stripe/webhook');
        });
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
            __DIR__ . '/../config/billing.php' => config_path('laravel-billing.php'),
        ], 'laravel-billing-config');

        // Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-billing'),
        ], 'laravel-billing-views');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/update_users_table.php.stub' => database_path('migrations/' . date('Y_m_d_His_', time()) . 'update_users_table.php'),
            __DIR__ . '/../database/migrations/create_subscriptions_table.php.stub' => database_path('migrations/' . date('Y_m_d_His_', time() + 5) . '_create_subscriptions_table.php'),
            __DIR__ . '/../database/migrations/create_subscription_items_table.php.stub' => database_path('migrations/' . date('Y_m_d_His_', time() + 10) . '_create_subscription_items_table.php'),
            __DIR__ . '/../database/migrations/create_tax_rates_table.php.stub' => database_path('migrations/' . date('Y_m_d_His_', time() + 15) . 'create_tax_rates_table.php'),
        ], 'laravel-billing-migrations');
    }
}
