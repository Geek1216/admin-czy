<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ __('Redirecting') }}&hellip;</title>
</head>
<body>
<p>{{ __('Please wait while we send you to payment gateway') }}&hellip;</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
    Stripe('{{ config('services.stripe.publishable_key') }}')
        .redirectToCheckout({ sessionId: '{{ $session_id }}' });
</script>
</body>
</html>
