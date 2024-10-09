<?php

namespace MrThito\LaravelBilling\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class LaravelBillingCommand extends Command
{
    public $signature = 'install:laravel-billing';

    public $description = 'Install the LaravelBilling resources';

    public function handle(): int
    {
        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%] %message%');
        $this->info('Installing Laravel Billing...');
        $progressBar = $this->output->createProgressBar(3);
        $progressBar->setFormat('custom');
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        $this->callSilent('vendor:publish', ['--tag' => 'laravel-billing-config']);
        $progressBar->setMessage('Working...');
        $progressBar->advance();

        $this->callSilent('vendor:publish', ['--tag' => 'laravel-billing-views']);
        $progressBar->setMessage('Almost there...');
        $progressBar->advance();

        $this->callSilent('vendor:publish', ['--tag' => 'laravel-billing-migrations']);
        $progressBar->setMessage('Finished!');
        $progressBar->finish();

        $this->info("\nLaravel Billing installed successfully.");

        return self::SUCCESS;
    }
}
