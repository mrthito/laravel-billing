<?php

namespace MrThito\LaravelBilling\Events;

use Laravel\Cashier\Subscription;

class LaravelBillingSubscriptionCancelled
{
    public function __construct(
        public $user,
        public Subscription $subscription,
    ) {}
}
