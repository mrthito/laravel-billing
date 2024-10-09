<?php

namespace MrThito\LaravelBilling\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use MrThito\LaravelBilling\LaravelBillingServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MrThito\\LaravelBilling\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelBillingServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-billing_table.php.stub';
        $migration->up();
        */
    }
}
