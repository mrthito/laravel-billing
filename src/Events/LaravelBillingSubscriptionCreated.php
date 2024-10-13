<?php

namespace MrThito\LaravelBilling\Events;

use Laravel\Cashier\Subscription;

class LaravelBillingSubscriptionCreated
{
    public function __construct(
        public $user,
        public Subscription $subscription,
    ) {}
}
