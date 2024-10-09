<?php

use App\Models\User;

return [

    /**
     * Routes to use for billing.
     */
    'routes' => [
        'path' => 'billing',
        'middleware' => ['web', 'auth'],
    ],

    /**
     * The branding for the billing portal.
     */
    'branding' => [
        'logo' => realpath(__DIR__.'/../public/svg/billing-logo.svg'),
        'colors' => [
            'primary' => '#000000',
            'secondary' => '#000000',
        ],
    ],

    /**
     * Date and time formats to use.
     */
    'date_time' => [
        'date_format' => 'Y-m-d',
        'time_format' => 'H:i:s',
    ],

    /**
     * The invoicing settings to use.
     */
    'invocing' => [
        'prorate' => true, // Prorate charges when making adjustments to a plan.

        /**
         * The company to use for invoicing.
         */
        'company' => [
            'name' => 'Your Company Name',
            'product' => 'Your Awesome Product',
            'address' => 'Somewhere in the world',
            'location' => 'Kathmandu, Nepal',
            'phone' => '9999999999',
        ],
    ],

    /**
     * The features for package use on billing process.
     */
    'features' => [
        'accept_terms' => true,
        'eu_vat' => [
            'country' => 'BE',
        ],
        'email' => [
            'invoice' => true,
            'notification' => [
                'status' => true,
                'emails' => [
                    'your@email.domain',
                ],
            ],
        ],
    ],

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
         * The model to use for the user.
         */
        'user_model' => User::class,

        /**
         * The trial settings to use.
         */
        'enable_trial' => true,

        /**
         * The number of days for the trial.
         */
        'trial_days' => 7,

        /**
         * The default interval to use.
         */
        'default_interval' => 'monthly',

        /**
         * The plans to use for the stripe provider.
         */
        'plans' => [
            [
                'name' => 'Basic',
                'description' => 'A basic plan',
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
