<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ __('Redirecting') }}&hellip;</title>
</head>
<body>
<p>{{ __('Please wait while we send you to payment gateway') }}&hellip;</p>
<form action="https://api.razorpay.com/v1/checkout/embedded" name="form-redirect" method="post">
    <input type="hidden" name="key_id" value="{{ config('services.razorpay.key_id') }}">
    <input type="hidden" name="order_id" value="{{ $order_id }}">
    <input type="hidden" name="name" value="{{ config('app.name') }}">
    <input type="hidden" name="description" value="{{ __('Recharge') }}">
    <input type="hidden" name="callback_url" value="{{ $callback_url }}">
    <input type="hidden" name="cancel_url" value="{{ $cancel_url }}">
</form>
<script>
    document.forms['form-redirect'].submit()
</script>
</body>
</html>
