<?php

namespace MrThito\LaravelBilling\Commands;

use Illuminate\Console\Command;

class LaravelBillingCommand extends Command
{
    public $signature = 'install:laravel-billing';

    public $description = 'Install the LaravelBilling resources';

    public function handle(): int
    {
        // WIP
        $this->success('Laravel Billing installed successfully.');

        return self::SUCCESS;
    }
}
