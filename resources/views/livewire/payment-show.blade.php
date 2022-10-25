@section('meta')
    <title>{{ __('Payments') }} &raquo; {{ $payment->reference_short }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">{{ __('Payments') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $payment->reference_short }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('payments.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Payments') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('payments.update', $payment) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('payments.destroy', $payment) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing payment here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $payment->user) }}">{{ $payment->user->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Reference') }}</th>
                                <td class="w-100">{{ $payment->reference }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Amount') }}</th>
                                <td class="w-100">
                                    @if (setting('payment_currency', config('fixtures.payment_currency')) === 'BTC')
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ (float) $payment->amount }}
                                    @else
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ $payment->amount / 100 }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Status') }}</th>
                                <td class="w-100">
                                    @if ($payment->status === 'successful')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle mr-1"></i> {{ __('Successful') }}
                                        </span>
                                    @elseif ($payment->status === 'refunded')
                                        <span class="text-info">
                                            <i class="fas fa-info-circle mr-1"></i> {{ __('Refunded') }}
                                        </span>
                                    @elseif ($payment->status === 'failed')
                                        <span class="text-danger">
                                            <i class="fas fa-times-circle mr-1"></i> {{ __('Failed') }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-question-circle mr-1"></i> {{ __('Pending') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Gateway') }}</th>
                                <td class="w-100">
                                    @if (empty($payment->data['gateway']))
                                        <span class="text-muted">{{ __('Unknown') }}</span>
                                    @else
                                        {{ config('fixtures.payment_gateways.' . $payment->data['gateway']) }}
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $payment->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $payment->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                @include('partials.activity_logs')
            </div>
        </div>
    </div>
</div>
