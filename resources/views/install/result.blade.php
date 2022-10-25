@extends('layouts.auth', ['main_columns' => 'col-md-10 col-lg-9 col-xl-8'])

@section('meta')
    <title>{{ __('Install') }} &raquo; {{ __('Result') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-primary">{{ __('Result') }}</h5>
            <p class="card-text">
                @if ($success)
                    {{ __('Congratulations! The installation has finished successfully.') }}
                @else
                    {{ __('Oops. The installation has failed for some reason.') }}
                @endif
            </p>
        </div>
        <div class="bg-dark p-3">
            <pre class="text-white mb-0">{{ trim($output) }}</pre>
        </div>
        @if ($success)
            <div class="card-body border-top">
                <div class="btn-toolbar">
                    <a class="btn btn-primary ml-auto" href="{{ route('login') }}" target="_blank">
                        {{ __('Login') }} <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
            </div>
        @else
            <div class="card-body border-top">
                <div class="btn-toolbar">
                    <a class="btn btn-outline-dark" href="{{ route('install.finalize') }}">
                        <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
