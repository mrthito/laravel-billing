<?php

// config for Laravelbilling/LaravelBilling
return [
    /**
     * The provider to use for billing. Currently only 'stripe' is supported.
     */
    'provider' => 'stripe',

    /**
     * The default plan to use when creating a new subscription.
     */
    'stripe' => [
        /**
         * The keys to use for the stripe provider.
         */
        'auth' => [
            'key' => env('LARAVEL_BILLING_STRIPE_KEY'),
            'secret' => env('LARAVEL_BILLING_STRIPE_SECRET'),
            'webhook_secret' => env('LARAVEL_BILLING_STRIPE_WEBHOOK_SECRET'),
        ],

        /**
         * The plans to use for the stripe provider.
         */
        'plans' => [
            [
                'name' => 'Basic',
                'trial_days' => 7,
                'monthly_price_id' => env('LARAVEL_BILLING_STRIPE_BASIC_MONTHLY_PRICE_ID'),
                'yearly_price_id' => env('LARAVEL_BILLING_STRIPE_BASIC_YEARLY_PRICE_ID'),
                'features' => [
                    'Feature 1',
                    'Feature 2',
                    'Feature 3',
                ],
                'data' => [
                    'posts' => '10',
                    'users' => '1',
                ],
            ],
        ],
    ],


];
