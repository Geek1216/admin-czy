@section('meta')
    <title>{{ __('Payments') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Payments') }}</li>
            </ol>
        </nav>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Payments') }}</h5>
                <p class="card-text">
                    {{ __('List and manage user payments here.') }}
                    <a href="" wire:click.prevent="filter()">
                        {{ __($filtering ? 'Hide filters?' : 'Show filters?') }}
                    </a>
                </p>
            </div>
            @if ($filtering)
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group mb-sm-0">
                                <label for="filter-search">{{ __('Search') }}</label>
                                <input id="filter-search" class="form-control" placeholder="{{ __('Enter reason or message') }}&hellip;" wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group mb-md-0">
                                <label for="filter-status">{{ __('Status') }}</label>
                                <select id="filter-status" class="form-control" wire:model="status">
                                    <option value="">{{ __('Any') }}</option>
                                    @foreach (config('fixtures.payment_statuses') as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 offset-lg-3">
                            <div class="form-group mb-0">
                                <label for="filter-length">{{ __('Length') }}</label>
                                <select id="filter-length" class="form-control" wire:model="length">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>
                            @if (($order['created_at'] ?? null) === 'asc')
                                <a class="text-body" href="" wire:click.prevent="sort('created_at', 'desc')">{{ __('Created at') }}</a>
                                <i class="fas fa-sort-amount-down-alt ml-1"></i>
                            @elseif (($order['created_at'] ?? null) === 'desc')
                                <a class="text-body" href="" wire:click.prevent="sort('created_at', false)">{{ __('Created at') }}</a>
                                <i class="fas fa-sort-amount-down ml-1"></i>
                            @else
                                <a class="text-body" href="" wire:click.prevent="sort('created_at', 'asc')">{{ __('Created at') }}</a>
                            @endif
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <a href="{{ route('users.show', $payment->user) }}">{{ $payment->user->name }}</a>
                            </td>
                            <td><abbr title="{{ $payment->reference }}">{{ $payment->reference_short }}</abbr></td>
                            <td>
                                <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span>
                                {{ setting('payment_currency', config('fixtures.payment_currency')) === 'BTC' ? (float) $payment->amount : $payment->amount / 100 }}
                            </td>
                            <td>
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
                            <td>{{ $payment->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <a class="btn btn-outline-dark btn-sm" href="{{ route('payments.show', $payment) }}">
                                    <i class="fas fa-eye mr-1"></i> {{ __('Details') }}
                                </a>
                                <a class="btn btn-info btn-sm" href="{{ route('payments.update', $payment) }}">
                                    <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                                </a>
                                @can('administer')
                                    <a class="btn btn-danger btn-sm" href="{{ route('payments.destroy', $payment) }}">
                                        <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted" colspan="7">{{ __('Could not find any payments to show.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payments->hasPages())
                <div class="card-body border-top">
                    {{ $payments->onEachSide(1)->links() }}
                </div>
            @endif
            <div class="card-body border-top">
                {{ __('Showing :from to :to of :total payments.', ['from' => $payments->firstItem() ?: 0, 'to' => $payments->lastItem() ?: 0, 'total' => $payments->total()]) }}
            </div>
        </div>
    </div>
</div>
