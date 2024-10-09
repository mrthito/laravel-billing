<?php

use Illuminate\Support\Facades\Route;
use MrThito\LaravelBilling\Http\Controllers\StripeBillingPortalController;


Route::prefix(config('laravel-billing.routes.path'))
    ->middleware(config('laravel-billing.routes.middleware', ['web', 'auth']))
    ->group(function () {
        Route::get('/{pmtype?}/{pmid?}', StripeBillingPortalController::class)->name('laravel-billing.portal');
    });
Route::prefix('laravel-billing')
    ->group(function () {
        # Stripe Webhook
        Route::post('stripe/webhook', 'WebhookController@handleWebhook');

        Route::middleware(config('laravel-billing.routes.middleware', ['web', 'auth']))
            ->group(function () {
                # WIP
            });
    });
