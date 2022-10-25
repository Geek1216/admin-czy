@section('meta')
    <title>{{ __('Users') }} &raquo; {{ $user->name }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

@section('body')
    @livewire('send-notification', ['user' => $user->id])
    @parent
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('users.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Users') }}
            </a>
            <button class="btn btn-warning ml-auto" data-toggle="modal" data-target="#modal-notification">
                <i class="fas fa-bullhorn mr-1"></i> {{ __('Notification') }}
            </button>
            @if (!$user->can('manage') || Gate::check('administer'))
                <a class="btn btn-info ml-1" href="{{ route('users.update', $user) }}">
                    <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                </a>
            @endif
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('users.destroy', $user) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing user here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Name') }}</th>
                                <td class="w-100">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Username') }}</th>
                                <td class="w-100">&commat;{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Email address') }}</th>
                                <td class="w-100">
                                    @if ($user->email)
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Phone number') }}</th>
                                <td class="w-100">
                                    @if ($user->phone)
                                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Password') }}</th>
                                <td class="w-100 text-muted">&ast;&ast;&ast;&ast;&ast;</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Role') }}</th>
                                <td class="w-100">
                                    @if ($user->role)
                                        {{ config('fixtures.user_roles.' . $user->role) }}
                                    @else
                                        <span class="text-muted">{{ __('None') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Enabled?') }}</th>
                                <td class="w-100">
                                    @if ($user->enabled)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Photo') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($user->photo)
                                        <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($user->photo) }}" rel="noopener noreferrer" target="_blank">{{ $user->photo }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Bio') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($user->bio)
                                        {{ $user->bio }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Verified?') }}</th>
                                <td class="w-100">
                                    @if ($user->verified)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Business?') }}</th>
                                <td class="w-100">
                                    @if ($user->business)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Links') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($user->links)
                                        @foreach($user->links as $link)
                                            <i class="fab fa-{{ $link['type'] }} text-{{ $link['type'] }} mr-1"></i>
                                            <a class="text-{{ $link['type'] }}" href="{{ $link['url'] }}" target="_blank">
                                                {{ config('fixtures.link_types.' . $link['type']) }}
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">{{ __('None') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($user->location)
                                <tr>
                                    <th class="bg-light">{{ __('Location') }}</th>
                                    <td class="w-100">
                                        <a href="http://maps.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}" rel="noopener noreferrer" target="_blank">
                                            {{ $user->location }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th class="bg-light">{{ __('Clips') }}</th>
                                <td class="w-100">{{ $user->clips()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Views') }}</th>
                                <td class="w-100">{{ $user->views_total }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Likes') }}</th>
                                <td class="w-100">{{ $user->likes_total }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Followers') }}</th>
                                <td class="w-100">{{ $user->followers()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Followings') }}</th>
                                <td class="w-100">{{ $user->followings()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Favorites') }}</th>
                                <td class="w-100">{{ $user->favorites()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Reports') }}</th>
                                <td class="w-100">{{ $user->reports()->count() }}</td>
                            </tr>
                            @if (config('fixtures.gifts_enabled'))
                                <tr>
                                    <th class="bg-light">{{ __('Balance') }}</th>
                                    <td class="w-100">
                                        @php
                                            $user->wallet->refreshBalance();
                                        @endphp
                                        {{ $user->balance }} <span class="text-muted">{{ __('Credits') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('Redemption mode') }}</th>
                                    <td class="w-100">
                                        @if ($user->redemption_mode)
                                            {{ config('fixtures.redemption_modes.' . $user->redemption_mode) }}
                                        @else
                                            <span class="text-muted">{{ __('Empty') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('Redemption address') }}</th>
                                    <td class="w-100">
                                        @if ($user->redemption_address)
                                            {{ $user->redemption_address }}
                                        @else
                                            <span class="text-muted">{{ __('Empty') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $user->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $user->updated_at->format('d/m/Y H:i') }}
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
