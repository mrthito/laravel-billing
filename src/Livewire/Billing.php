<?php

namespace MrThito\LaravelBilling\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Livewire\Component;

class Billing extends Component
{
    public $loading = true;

    public $plans = [];

    public $billingToggle = 'yearly';

    public function init()
    {
        $user = Auth::user();
        $plans = config('laravel-billing.stripe.plans', []);
        $this->plans = collect($plans)->map(function ($plan) use ($user) {
            $monthlyPrice = 0;
            $monthCurrency = 'usd';
            $cashierPrice = Cashier::stripe()->prices->retrieve($plan['monthly_price_id']);
            if ($cashierPrice) {
                $monthlyPrice = $cashierPrice->unit_amount;
                $monthCurrency = $cashierPrice->currency;
            }

            $yearlyPrice = 0;
            $yearCurrency = 'usd';
            $cashierPrice = Cashier::stripe()->prices->retrieve($plan['yearly_price_id']);
            if ($cashierPrice) {
                $yearlyPrice = $cashierPrice->unit_amount;
                $yearCurrency = $cashierPrice->currency;
            }

            return [
                'id' => uniqid(),
                'name' => $plan['name'],
                'description' => $plan['description'],
                'monthly_price_id' => $plan['monthly_price_id'],
                'monthly_price' => $monthlyPrice,
                'monthly_currency' => $monthCurrency,
                'yearly_price_id' => $plan['yearly_price_id'],
                'yearly_price' => $yearlyPrice,
                'yearly_currency' => $yearCurrency,
                'features' => $plan['features'],
                'options' => $plan['options'],
                'current' => $user->subscribedToPrice($plan['yearly_price_id'], 'laravel-billing-subscription'),
            ];
        });

        $this->loading = false;
    }

    public function render()
    {
        return view('laravel-billing::portal')
            ->layout('laravel-billing::layouts.app');
    }

    public function choosePlan($plan)
    {
        $plan = collect($this->plans)->firstWhere('id', $plan);
        $user = User::first();
        // If stripe customer id is not set, create a new customer
        if (! $user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        // If user is already subscribed to a plan, swap the plan
        // dd($user->redirectToBillingPortal(), $user->subscribed());
        if ($user->subscribed()) {
            // $user->subscription('default')->swap($plan['yearly_price_id']);
            dd('swap');
        } else {
            $priceId = $this->billingToggle === 'yearly' ? $plan['yearly_price_id'] : $plan['monthly_price_id'];
            $trial = config('laravel-billing.stripe.enable_trial', false);
            if ($trial) {
                $trial = config('laravel-billing.stripe.trial_days', 5);
            }
            $user = $user
                ->newSubscription('laravel-billing-subscription', $priceId);
            if ($trial) {
                $user = $user->trialDays($trial);
            }
            $user = $user->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('laravel-billing.portal'),
                    'cancel_url' => route('laravel-billing.portal'),
                ]);
        }

        return $user;
    }

    public function saveAddress()
    {
        $this->validate([
            'form.address' => 'required',
            'form.city' => 'required',
            'form.state' => 'required',
            'form.zip' => 'required',
            'form.country' => 'required',
        ]);
    }
}
