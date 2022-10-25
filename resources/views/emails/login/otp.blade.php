@component('mail::message')
# OTP

Please find below your OTP to login.

@component('mail::panel')
{{ $otp }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
