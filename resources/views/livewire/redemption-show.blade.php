@section('meta')
    <title>{{ __('Redemptions') }} &raquo; #{{ $redemption->id }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('redemptions.index') }}">{{ __('Redemptions') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $redemption->id }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('redemptions.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Redemptions') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('redemptions.update', $redemption) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('redemptions.destroy', $redemption) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing redemption here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $redemption->user) }}">{{ $redemption->user->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Amount') }}</th>
                                <td class="w-100">
                                    @if (setting('payment_currency', config('fixtures.payment_currency')) === 'BTC')
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ (float) $redemption->amount }}
                                    @else
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ $redemption->amount / 100 }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Mode') }}</th>
                                <td class="w-100">{{ config('fixtures.redemption_modes.' . $redemption->mode) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Address') }}</th>
                                <td class="w-100">{{ $redemption->address }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Status') }}</th>
                                <td class="w-100">
                                    @if ($redemption->status === 'approved')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle mr-1"></i> {{ config('fixtures.redemption_statuses.approved') }}
                                        </span>
                                    @elseif ($redemption->status === 'rejected')
                                        <span class="text-danger">
                                            <i class="fas fa-ban text-danger mr-1"></i> {{ config('fixtures.redemption_statuses.rejected') }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-clock text-muted mr-1"></i> {{ config('fixtures.redemption_statuses.pending') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Notes') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($redemption->notes)
                                        {{ $redemption->notes }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $redemption->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $redemption->updated_at->format('d/m/Y H:i') }}
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
