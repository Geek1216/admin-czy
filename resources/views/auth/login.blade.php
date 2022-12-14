@extends('layouts.auth')

@section('meta')
    <title>{{ __('Login') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="card shadow-sm">
        @if (Route::has('register'))
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                </ul>
            </div>
        @endif
        <div class="card-body">
            <h5 class="card-title text-primary">{{ __('Login') }}</h5>
            @if (Route::has('register'))
                <p class="card-text">
                    {{ __('If you do not have an account yet') }}, <a href="{{ route('register') }}">{{ __('create one here') }}</a>.
                </p>
            @endif
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="login-username">{{ __('Username') }} <span class="text-danger">&ast;</span></label>
                    <input autofocus class="form-control @error('username') is-invalid @enderror" id="login-username" name="username" required value="{{ old('username') }}">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="login-password">{{ __('Password') }} <span class="text-danger">&ast;</span></label>
                    <input class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" required type="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <p class="text-right">
                        <small>{{ __('Forgot password?') }} <a href="{{ route('password.request') }}">{{ __('Reset here.') }}</a></small>
                    </p>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="login-remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="login-remember">{{ __('Do not ask again?') }}</label>
                    </div>
                </div>
                <button class="btn btn-secondary">
                    {{ __('Login') }} <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </form>
        </div>
    </div>
@endsection
