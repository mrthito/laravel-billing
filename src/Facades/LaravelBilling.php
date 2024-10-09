<?php

namespace MrThito\LaravelBilling\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MrThito\LaravelBilling\LaravelBilling
 */
class LaravelBilling extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MrThito\LaravelBilling\LaravelBilling::class;
    }
}
