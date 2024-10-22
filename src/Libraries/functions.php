<?php

if (!function_exists('lb_subscribed')) {
    function lb_subscribed()
    {
        return auth()->user()->subscribed('laravel-billing-subscription');
    }
}

if (!function_exists('lb_subscription')) {
    function lb_subscription()
    {
        return auth()->user()->subscription('laravel-billing-subscription');
    }
}

if (!function_exists('lb_current_plan')) {
    function lb_current_plan()
    {
        $user = auth()->user();
        $currentPlan = $user->subscription('laravel-billing-subscription')->stripe_price;
        $plan = collect(config('laravel-billing.stripe.plans'))->first(function ($plan) use ($currentPlan) {
            return $plan['monthly_price_id'] === $currentPlan || $plan['yearly_price_id'] === $currentPlan;
        });
        if ($plan && $plan['yearly_price_id'] === $currentPlan) {
            $interval = 'yearly';
        } else {
            $interval = 'monthly';
        }

        return [
            'name' => $plan['name'],
            'description' => $plan['description'],
            'interval' => $interval
        ];
    }

    if (!function_exists('lb_on_trial')) {
        function lb_on_trial()
        {
            return auth()->user()->onTrial('laravel-billing-subscription');
        }
    }
}
