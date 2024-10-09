@component('mail::message')
    # {{ __('Your :invoiceName invoice is now available!', ['invoiceName' => $invoice->date()->format('F Y')]) }}

    {{ __('We cannot thank you enough for your continued support. We\'ve attached a copy of your invoice for your records. You can also view your invoice by visiting the billing section of your account.') }}

    {{ __('Thanks,') }}<br>
    {{ config('app.name') }}
@endcomponent
