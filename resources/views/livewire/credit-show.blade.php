@section('meta')
<title>{{ __('Credits') }} &raquo; {{ $credit->title }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('credits.index') }}">{{ __('Credits') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $credit->title }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('credits.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Credits') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('credits.update', $credit) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
            <a class="btn btn-danger ml-1" href="{{ route('credits.destroy', $credit) }}">
                <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
            </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing credit here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light">{{ __('Title') }}</th>
                                    <td class="w-100">{{ $credit->title }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light align-text-top">{{ __('Description') }}</th>
                                    <td class="w-100 text-wrap">
                                        @if ($credit->description)
                                        {{ $credit->description }}
                                        @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('Price') }}</th>
                                    <td class="w-100">
                                        @if (setting('payment_currency', config('fixtures.payment_currency')) === 'BTC')
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ (float) $credit->price }}
                                        @else
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ $credit->price / 100 }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('Value') }}</th>
                                    <td class="w-100">
                                        {{ $credit->value }} <span class="text-muted">{{ __('Credits') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('Order') }}</th>
                                    <td class="w-100">{{ $credit->order }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light align-text-top">{{ __('Play Store ID') }}</th>
                                    <td class="w-100 text-wrap">
                                        @if ($credit->play_store_product_id)
                                        {{ $credit->play_store_product_id }}
                                        @else
                                        <span class="text-muted">{{ __('Not set') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if($credit->created_at)
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $credit->created_at->format('d/m/Y H:i') }}
                        @else
                        <span class="text-muted">{{ __('Created at') }}</span> N/A
                        @endif
                        <span class="d-none d-md-inline">
                            &bull;
                            @if($credit->updated_at)
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $credit->updated_at->format('d/m/Y H:i') }}
                            @else
                            <span class="text-muted">{{ __('Updated at') }}</span> N/A
                            @endif
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