@section('meta')
    <title>{{ __('Users') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('users.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Register a new user manually.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-name">{{ __('Name') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('name') is-invalid @enderror" id="user-name" required wire:model="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-username">{{ __('Username') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('username') is-invalid @enderror" id="user-username" required wire:model="username">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-email">{{ __('Email') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('email') is-invalid @enderror" id="user-email" type="email" wire:model="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-phone">{{ __('Phone') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('phone') is-invalid @enderror" id="user-phone" type="tel" wire:model="phone">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-password">{{ __('Password') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('password') is-invalid @enderror" id="user-password" required type="password" wire:model="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @can('administer')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="user-role">{{ __('Role') }}</label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('role') is-invalid @enderror" id="user-role" wire:model="role">
                                            <option value="">{{ __('None') }}</option>
                                            @foreach (config('fixtures.user_roles') as $code => $name)
                                                <option value="{{ $code }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endcan
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-enabled">{{ __('Enabled?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="user-enabled" type="checkbox" wire:model="enabled">
                                        <label class="custom-control-label" for="user-enabled">{{ __('Yes') }}</label>
                                    </div>
                                    @error('enabled')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-verified">{{ __('Verified?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="user-verified" type="checkbox" wire:model="verified">
                                        <label class="custom-control-label" for="user-verified">{{ __('Yes') }}</label>
                                    </div>
                                    @error('verified')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="user-business">{{ __('Business?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="user-business" type="checkbox" wire:model="business">
                                        <label class="custom-control-label" for="user-business">{{ __('Yes') }}</label>
                                    </div>
                                    @error('business')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4">
                                    <button class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i> {{ __('Create') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
