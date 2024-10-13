@component('mail::message')
    # {{ __('Invoice #:invoiceNumber', ['invoiceNumber' => $invoice->date()->format(config('laravel-billing.date_time.date_format', 'M d, Y'))]) }}

    {{ __('We sincerely appreciate your ongoing support. A copy of your invoice is attached for your records. You can also access it anytime by visiting the billing section of your account.') }}

    {{ __('Thanks,') }}<br>
    {{ config('app.name') }}
@endcomponent
