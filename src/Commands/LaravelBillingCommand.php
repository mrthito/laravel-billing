<?php

namespace MrThito\LaravelBilling\Commands;

use Illuminate\Console\Command;

class LaravelBillingCommand extends Command
{
    public $signature = 'laravel-billing';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
