@section('meta')
    <title>{{ __('Suggestions') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Suggestions') }}</li>
            </ol>
        </nav>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Suggestions') }}</h5>
                <p class="card-text">
                    {{ __('List and manage registered suggestions here.') }}
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
                        <div class="col-sm-6 col-md-4 col-lg-3 offset-md-4 offset-lg-6">
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
                        <th>{{ __('Followers') }}</th>
                        <th>
                            @if (($order['order'] ?? null) === 'asc')
                                <a class="text-body" href="" wire:click.prevent="sort('order', 'desc')">{{ __('Sort order') }}</a>
                                <i class="fas fa-sort-amount-down-alt ml-1"></i>
                            @elseif (($order['order'] ?? null) === 'desc')
                                <a class="text-body" href="" wire:click.prevent="sort('order', false)">{{ __('Sort order') }}</a>
                                <i class="fas fa-sort-amount-down ml-1"></i>
                            @else
                                <a class="text-body" href="" wire:click.prevent="sort('order', 'asc')">{{ __('Sort order') }}</a>
                            @endif
                        </th>
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
                    @forelse ($suggestions as $suggestion)
                        <tr>
                            <td>{{ $suggestion->id }}</td>
                            <td>
                                @if ($suggestion->user->photo)
                                    <img alt="{{ $suggestion->user->name }}" class="rounded-circle" height="32" src="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($suggestion->user->photo) }}">
                                @endif
                            </td>
                            <td>{{ $suggestion->user->name }}</td>
                            <td>&commat;{{ $suggestion->user->username }}</td>
                            <td>{{ $suggestion->user->followers()->count() }}</td>
                            <td>{{ $suggestion->order }}</td>
                            <td>{{ $suggestion->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <a class="btn btn-outline-dark btn-sm" href="{{ route('suggestions.show', $suggestion) }}">
                                    <i class="fas fa-eye mr-1"></i> {{ __('Details') }}
                                </a>
                                <a class="btn btn-info btn-sm" href="{{ route('suggestions.update', $suggestion) }}">
                                    <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                                </a>
                                @can('administer')
                                    <a class="btn btn-danger btn-sm" href="{{ route('suggestions.destroy', $suggestion) }}">
                                        <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted" colspan="8">{{ __('Could not find any suggestions to show.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if ($suggestions->hasPages())
                <div class="card-body border-top">
                    {{ $suggestions->onEachSide(1)->links() }}
                </div>
            @endif
            <div class="card-body border-top">
                {{ __('Showing :from to :to of :total suggestions.', ['from' => $suggestions->firstItem() ?: 0, 'to' => $suggestions->lastItem() ?: 0, 'total' => $suggestions->total()]) }}
            </div>
        </div>
    </div>
</div>
