<?php

namespace MrThito\LaravelBilling\Events;

use Laravel\Cashier\Subscription;

class LaravelBillingSubscriptionUpdated
{
    public function __construct(
        public $user,
        public Subscription $subscription,
    ) {}
}
