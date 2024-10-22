<?php

namespace MrThito\LaravelBilling\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Livewire\Component;
use MrThito\LaravelBilling\Libraries\Countries;

class Billing extends Component
{
    public $loading = true;

    public $plans = [];

    public $billingToggle = 'yearly';

    public $countries;

    public $form = [];

    public $invoices = [];

    public $invoiceEmails = [];

    public $currentPlan = [];

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

        $this->form['billing_address'] = $user->billing_address;
        $this->form['billing_address_line_2'] = $user->billing_address_line_2;
        $this->form['billing_city'] = $user->billing_city;
        $this->form['billing_state'] = $user->billing_state;
        $this->form['billing_postal_code'] = $user->billing_postal_code;
        $this->form['billing_country'] = $user->billing_country;
        $this->form['extra_billing_information'] = $user->extra_billing_information;

        $emails = explode(', ', $user->invoice_emails);
        $this->invoiceEmails = $emails;

        $this->invoices = $user->invoices()->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'date' => $invoice->date()->format('M d, Y'),
                'total' => $invoice->total(),
                'status' => $invoice->status,
                'number' => $invoice->number,
            ];
        });

        $this->countries = Countries::get();

        $currentPlan = $user->subscription('laravel-billing-subscription')->stripe_price;
        // find the current plan by matching the price id either monthly or yearly
        $plan = collect($this->plans)->first(function ($plan) use ($currentPlan) {
            return $plan['monthly_price_id'] === $currentPlan || $plan['yearly_price_id'] === $currentPlan;
        });
        if ($plan && $plan['yearly_price_id'] === $currentPlan) {
            $interval = 'yearly';
        } else {
            $interval = 'monthly';
        }
        // dd($plan);

        $this->currentPlan = [
            'id' => $currentPlan,
            'interval' => $interval
        ];
        // dd($this->currentPlan);

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

        // check if user has address
        if (! $this->form['billing_address']) {
            session()->flash('alreadySubscribed', 'Please fill in your address before subscribing');
            return;
        }

        // If user is already subscribed to a plan, swap the plan
        if ($user->subscribed('laravel-billing-subscription')) {
            // get current subscription
            $subscription = $user->subscription('laravel-billing-subscription');
            $priceId = $subscription->stripe_price;
            // check if the current subscription is the same as the plan and user selected
            if ($priceId === ($this->billingToggle === 'yearly' ? $plan['yearly_price_id'] : $plan['monthly_price_id'])) {
                session()->flash('alreadySubscribed', 'You are already subscribed to this plan');
                return;
            }

            $user = $subscription->swap($this->billingToggle === 'yearly' ? $plan['yearly_price_id'] : $plan['monthly_price_id']);
            session()->flash('subscriptionSwapped', 'Subscription swapped successfully');
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
            'form.billing_address' => 'required',
            'form.billing_address_line_2' => 'required',
            'form.billing_city' => 'required',
            'form.billing_state' => 'required',
            'form.billing_postal_code' => 'required',
            'form.billing_country' => 'required',
            'form.extra_billing_information' => 'required',
        ], [
            'form.billing_address.required' => 'The billing address field is required.',
            'form.billing_address_line_2.required' => 'The billing address line 2 field is required.',
            'form.billing_city.required' => 'The billing city field is required.',
            'form.billing_state.required' => 'The billing state field is required.',
            'form.billing_postal_code.required' => 'The billing postal code field is required.',
            'form.billing_country.required' => 'The billing country field is required.',
            'form.extra_billing_information.required' => 'The extra billing information field is required.',
        ]);

        $user = Auth::user();
        $user->update([
            'extra_billing_information' => $this->form['extra_billing_information'],
            'billing_address' => $this->form['billing_address'],
            'billing_address_line_2' => $this->form['billing_address_line_2'],
            'billing_city' => $this->form['billing_city'],
            'billing_state' => $this->form['billing_state'],
            'billing_postal_code' => $this->form['billing_postal_code'],
            'billing_country' => $this->form['billing_country'],
        ]);

        session()->flash('addressSaved', 'Address saved successfully');
    }

    public function saveInvoiceEmails()
    {
        $this->validate([
            'invoiceEmails' => 'required|array',
            'invoiceEmails.*' => 'email:rfc,dns',
        ], [
            'invoiceEmails.required' => 'The invoice emails field is required.',
            'invoiceEmails.*.email' => 'The invoice emails must be a valid email address.',
        ]);

        $user = Auth::user();
        $user->update([
            'invoice_emails' => implode(', ', $this->invoiceEmails),
        ]);

        session()->flash('emailsSaved', 'Emails saved successfully');
    }

    public function addInvoiceEmail()
    {
        $this->invoiceEmails[] = '';
    }
    public function removeInvoiceEmail($index)
    {
        unset($this->invoiceEmails[$index]);
        // make sure to reindex the array
        $this->invoiceEmails = array_values($this->invoiceEmails);
    }

    public function downloadInvoice($invoiceId)
    {
        $url = route('laravel-billing.invoice.download', ['invoice' => $invoiceId]);
        session()->flash('successInvoice', 'Invoice downloaded successfully');
        return redirect($url);
    }

    public function toggleBilling($toggle)
    {
        $this->billingToggle = $toggle;
    }
}
