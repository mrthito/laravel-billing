<?php

namespace MrThito\LaravelBilling\Http\Controllers;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Laravel\Cashier\Payment;
use MrThito\LaravelBilling\Events\LaravelBillingPaymentSuccesded;
use MrThito\LaravelBilling\Events\LaravelBillingSubscriptionCancelled;
use MrThito\LaravelBilling\Events\LaravelBillingSubscriptionCreated;
use MrThito\LaravelBilling\Events\LaravelBillingSubscriptionUpdated;
use MrThito\LaravelBilling\Mail\PaymentNeedConfirmMail;
use MrThito\LaravelBilling\Mail\SendInvoiceMail;
use Stripe\Subscription;

class StripeWebhookController extends WebhookController
{
    protected function handleCustomerSubscriptionCreated(array $payload)
    {
        $response = parent::handleCustomerSubscriptionCreated($payload);

        $customer = $payload['data']['object']['customer'];
        $user = $this->getUserByStripeId($customer);

        if ($user) {
            $stripe_id = $payload['data']['object']['id'];
            $subscription = $user->subscriptions()->where('stripe_id', $stripe_id)->first();

            if ($subscription && $subscription->stripe_status === Subscription::STATUS_ACTIVE) {
                event(new LaravelBillingSubscriptionCreated($user, $subscription->refresh()));
            }
        }

        return $response;
    }

    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $customer = $payload['data']['object']['customer'];
        $user = $this->getUserByStripeId($customer);

        if ($user) {
            parent::handleCustomerSubscriptionUpdated($payload);

            $stripe_id = $payload['data']['object']['id'];
            $subscription = $user->subscriptions()->where('stripe_id', $stripe_id)->first();

            if ($subscription) {
                $oldStatus = $subscription->stripe_status;
                $newStatus = $payload['data']['object']['status'] ?? null;

                if (
                    $newStatus &&
                    $newStatus == Subscription::STATUS_ACTIVE &&
                    ! in_array($oldStatus, [
                        Subscription::STATUS_TRIALING,
                        Subscription::STATUS_ACTIVE,
                    ])
                ) {
                    event(new LaravelBillingSubscriptionCreated($user, $subscription->refresh()));
                } else {
                    event(new LaravelBillingSubscriptionUpdated($user, $subscription->refresh()));
                }
            }
        }

        return $this->successMethod();
    }

    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        $customer = $payload['data']['object']['customer'];
        $user = $this->getUserByStripeId($customer);

        if ($user) {
            parent::handleCustomerSubscriptionDeleted($payload);

            $stripe_id = $payload['data']['object']['id'];
            $subscription = $user->subscriptions()->where('stripe_id', $stripe_id)->first();

            if ($subscription) {
                event(new LaravelBillingSubscriptionCancelled($user, $subscription));
            }
        }

        return $this->successMethod();
    }

    protected function handleCustomerDeleted(array $payload)
    {
        $customer = $payload['data']['object']['customer'];
        $user = $this->getUserByStripeId($customer);

        if ($user) {
            parent::handleCustomerDeleted($payload);

            $stripe_id = $payload['data']['object']['id'];
            $subscription = $user->subscriptions()->where('stripe_id', $stripe_id)->first();

            event(new LaravelBillingSubscriptionCancelled($user, $subscription));
        }

        return $this->successMethod();
    }

    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $customer = $payload['data']['object']['customer'];
        $user = $this->getUserByStripeId($customer);

        if ($user) {
            $object_id = $payload['data']['object']['id'];
            $invoice = $user->findInvoice($object_id);

            if ($invoice) {
                $this->sendUserInvoiceNotificationEmail($user, $invoice);

                event(new LaravelBillingPaymentSuccesded($user, $invoice));
            }
        }

        return $this->successMethod();
    }

    protected function handleInvoicePaymentActionRequired(array $payload)
    {
        if ($payload['data']['object']['metadata']['is_on_session_checkout'] ?? false) {
            return $this->successMethod();
        }

        if ($payload['data']['object']['subscription_details']['metadata']['is_on_session_checkout'] ?? false) {
            return $this->successMethod();
        }

        $customer = $payload['data']['object']['customer'];
        $user = $this->getUserByStripeId($customer);
        if ($user) {
            if (in_array(
                Notifiable::class,
                class_uses_recursive($user)
            )) {
                $payment = new Payment($user->stripe()
                    ->paymentIntents
                    ->retrieve(
                        $payload['data']['object']['payment_intent']
                    ));

                $this->sendUserPaymentConfirmationNotificationEmail($user, $payment);
            }
        }

        return $this->successMethod();
    }

    protected function sendUserInvoiceNotificationEmail($user, $invoice)
    {
        if (config('laravel-billing.features.email.invoice') === false) {
            return;
        }

        if (config('laravel-billing.features.email.notification.status') === false) {
            return;
        }

        $emails = config('laravel-billing.features.email.notification.emails', []);

        Mail::to($emails)->send(new SendInvoiceMail($user, invoice: $invoice));
    }

    protected function sendUserPaymentConfirmationNotificationEmail($user, $payment)
    {
        if (config('laravel-billing.features.email.notification.status') === false) {
            return;
        }

        Mail::to($user->stripeEmail())->send(new PaymentNeedConfirmMail($payment));
    }
}
