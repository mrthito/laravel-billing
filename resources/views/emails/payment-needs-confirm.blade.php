@component('mail::message')
    # {{ __('Please confirm your payment of :amount', ['amount' => $amount]) }}

    {{ __('Additional confirmation is required to complete your payment. Please click the button below to proceed to the payment page.') }}

    @component('mail::button', ['url' => $url])
        {{ __('Confirm Payment') }}
    @endcomponent

    {{ __('Thanks,') }}<br>
    {{ config('app.name') }}
@endcomponent
