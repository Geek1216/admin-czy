@section('meta')
    <title>{{ __('Users') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    @include('partials.boost', ['record' => 'user'])
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
                <span class="sr-only">{{ __('Loading') }}&hellip;</span>
            </div>
            <h5 class="card-title text-primary">{{ __('Users') }}</h5>
            <p class="card-text">
                {{ __('List and manage registered users here.') }}
                <a href="" wire:click.prevent="filter()">
                    {{ __($filtering ? 'Hide filters?' : 'Show filters?') }}
                </a>
            </p>
        </div>
        @if ($filtering)
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group">
                            <label for="filter-search">{{ __('Search') }}</label>
                            <input id="filter-search" class="form-control" placeholder="{{ __('Enter name or email') }}&hellip;" wire:model.debounce.500ms="search">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group">
                            <label for="filter-role">{{ __('Role') }}</label>
                            <select id="filter-role" class="form-control" wire:model="role">
                                <option value="">{{ __('Any') }}</option>
                                @foreach (config('fixtures.user_roles') as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group">
                            <label for="filter-verified">{{ __('Verified?') }}</label>
                            <select id="filter-verified" class="form-control" wire:model="verified">
                                <option value="">{{ __('Any') }}</option>
                                <option value="true">{{ __('Yes') }}</option>
                                <option value="false">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group mb-md-0">
                            <label for="filter-business">{{ __('Business?') }}</label>
                            <select id="filter-business" class="form-control" wire:model="business">
                                <option value="">{{ __('Any') }}</option>
                                <option value="true">{{ __('Yes') }}</option>
                                <option value="false">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group mb-sm-0">
                            <label for="filter-enabled">{{ __('Enabled?') }}</label>
                            <select id="filter-enabled" class="form-control" wire:model="enabled">
                                <option value="">{{ __('Any') }}</option>
                                <option value="true">{{ __('Yes') }}</option>
                                <option value="false">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
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
                    <th></th>
                    <th>{{ __('Username') }}</th>
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
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            @if ($user->photo)
                                <img alt="{{ $user->name }}" class="rounded-circle" height="32" src="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($user->photo) }}">
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <i class="fab fa-facebook fa-fw @if (empty($user->facebook_id)) text-muted @else text-facebook @endif"></i>
                            <i class="fab fa-google fa-fw @if (empty($user->google_id)) text-muted @else text-google @endif"></i>
                            <i class="fas fa-phone-alt fa-fw @if (empty($user->phone)) text-muted @else text-info @endif"></i>
                            <i class="fas fa-at fa-fw @if (empty($user->email)) text-muted @else text-primary @endif"></i>
                        </td>
                        <td>
                            @if ($user->enabled)
                                &commat;{{ $user->username }}
                            @else
                                <del class="text-danger" title="{{ __('Disabled') }}">&commat;{{ $user->username }}</del>
                            @endif
                            @if ($user->verified)
                                <i class="fas fa-star text-primary ml-1" title="{{ __('Verified') }}"></i>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <a class="btn btn-outline-dark btn-sm" href="{{ route('users.show', $user) }}">
                                <i class="fas fa-eye mr-1"></i> {{ __('Details') }}
                            </a>
                            <div class="dropdown d-inline-block">
                                <a aria-expanded="false" aria-haspopup="true" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" href="" role="button">
                                    <i class="fas fa-rocket mr-1"></i> {{ __('Boost') }}
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" wire:click="showBoostDialog({{ $user->id }}, 'followers')">
                                        <i class="fas fa-user-friends fa-fw mr-1"></i> {{ __('Followers') }}
                                    </button>
                                </div>
                            </div>
                            <div class="btn-group d-inline-block">
                                <button class="btn btn-secondary btn-sm" @if ($user->suggestion()->exists()) disabled @endif wire:click="suggest({{ $user->id }}, false)">
                                    <i class="fas fa-plus mr-1"></i> {{ __('Suggest') }}
                                </button><button class="btn btn-secondary btn-sm" wire:click="suggest({{ $user->id }}, true)">
                                    <i class="fas fa-bullhorn fa-fw"></i>
                                </button>
                            </div>
                            @if (!$user->can('manage') || Gate::check('administer'))
                                <a class="btn btn-info btn-sm" href="{{ route('users.update', $user) }}">
                                    <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                                </a>
                            @endif
                            @can('administer')
                                <a class="btn btn-danger btn-sm" href="{{ route('users.destroy', $user) }}">
                                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-muted" colspan="7">{{ __('Could not find any users to show.') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-body border-top">
                {{ $users->onEachSide(1)->links() }}
            </div>
        @endif
        <div class="card-body border-top">
            {{ __('Showing :from to :to of :total users.', ['from' => $users->firstItem() ?: 0, 'to' => $users->lastItem() ?: 0, 'total' => $users->total()]) }}
        </div>
    </div>
</div>
