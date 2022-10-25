@section('meta')
    <title>{{ __('Verifications') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Verifications') }}</li>
            </ol>
        </nav>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Verifications') }}</h5>
                <p class="card-text">
                    {{ __('List and manage verification requests here.') }}
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
                                <input id="filter-search" class="form-control" placeholder="{{ __('Enter name or email') }}&hellip;" wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group mb-md-0">
                                <label for="filter-status">{{ __('Status') }}</label>
                                <select id="filter-status" class="form-control" wire:model="status">
                                    <option value="">{{ __('Any') }}</option>
                                    @foreach (config('fixtures.verification_statuses') as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 offset-md-4 offset-lg-3">
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
                        <th></th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Username') }}</th>
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
                    @forelse ($verifications as $verification)
                        <tr>
                            <td>{{ $verification->id }}</td>
                            <td>
                                @if ($verification->user->photo)
                                    <img alt="{{ $verification->user->name }}" class="rounded-circle" height="32" src="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($verification->user->photo) }}">
                                @endif
                            </td>
                            <td>{{ $verification->user->name }}</td>
                            <td>&commat;{{ $verification->user->username }}</td>
                            <td>
                                @if ($verification->status === 'accepted')
                                    <span class="text-success">
                                        <i class="fas fa-check-circle mr-1"></i> {{ __('Accepted') }}
                                    </span>
                                @elseif ($verification->status === 'rejected')
                                    <span class="text-danger">
                                        <i class="fas fa-times-circle mr-1"></i> {{ __('Rejected') }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-clock mr-1"></i> {{ __('Pending') }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $verification->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <a class="btn btn-outline-dark btn-sm" href="{{ route('verifications.show', $verification) }}">
                                    <i class="fas fa-eye mr-1"></i> {{ __('Details') }}
                                </a>
                                <a class="btn btn-info btn-sm" href="{{ route('verifications.update', $verification) }}">
                                    <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                                </a>
                                @can('administer')
                                    <a class="btn btn-danger btn-sm" href="{{ route('verifications.destroy', $verification) }}">
                                        <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted" colspan="7">{{ __('Could not find any verifications to show.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if ($verifications->hasPages())
                <div class="card-body border-top">
                    {{ $verifications->onEachSide(1)->links() }}
                </div>
            @endif
            <div class="card-body border-top">
                {{ __('Showing :from to :to of :total verifications.', ['from' => $verifications->firstItem() ?: 0, 'to' => $verifications->lastItem() ?: 0, 'total' => $verifications->total()]) }}
            </div>
        </div>
    </div>
</div>
