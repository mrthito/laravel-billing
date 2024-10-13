<?php

namespace MrThito\LaravelBilling\Events;

use Laravel\Cashier\Invoice;

class LaravelBillingPaymentSuccesded
{
    public function __construct(
        public $user,
        public Invoice $invoice,
    ) {}
}
