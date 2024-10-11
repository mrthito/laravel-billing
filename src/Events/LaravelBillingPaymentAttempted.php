<?php

namespace MrThito\LaravelBilling\Events;

use Laravel\Cashier\Invoice;

class LaravelBillingPaymentAttempted
{
    public function __construct(
        public $user,
        public Invoice $invoice,
    ) {}
}
