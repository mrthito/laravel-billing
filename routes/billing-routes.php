<?php

use Illuminate\Support\Facades\Route;
use MrThito\LaravelBilling\Http\Controllers\StripeInvoiceDownloadController;
use MrThito\LaravelBilling\Http\Controllers\StripeWebhookController;
use MrThito\LaravelBilling\Livewire\Billing;

Route::prefix(config('laravel-billing.routes.path'))
    ->middleware(config('laravel-billing.routes.middleware', ['web', 'auth']))
    ->group(function () {
        Route::get('/{pmtype?}/{pmid?}', Billing::class)->name('laravel-billing.portal');
    });
Route::prefix('laravel-billing')
    ->group(function () {
        // Stripe Webhook
        Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('laravel-billing.stripe.webhook');

        Route::middleware(config('laravel-billing.routes.middleware', ['web', 'auth']))
            ->group(function () {
                Route::get('invoice/download', StripeInvoiceDownloadController::class)->name('laravel-billing.invoice.download');
            });
    });
