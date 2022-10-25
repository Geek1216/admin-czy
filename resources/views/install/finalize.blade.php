@extends('layouts.auth', ['main_columns' => 'col-md-10 col-lg-9 col-xl-8'])

@section('meta')
    <title>{{ __('Install') }} &raquo; {{ __('Finalize') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    @php
        try {
            $pdo = DB::connection()->getPdo();
        } catch (Exception $e) {
        }

        $connected = isset($pdo);
    @endphp
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-primary">{{ __('Finalize') }}</h5>
            <p class="card-text">
                @if ($connected)
                    {{ __('Database connection was established successfully. You may now continue with the installation.') }}
                @else
                    {{ __('Connection with database could not be established. Please go back and check your credentials.') }}
                @endif
            </p>
        </div>
        <div class="card-body border-top">
            <form action="" method="post">
                @csrf
                <div class="btn-toolbar">
                    <a class="btn btn-outline-dark" href="{{ route('install.configure') }}">
                        <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
                    </a>
                    <button class="btn btn-primary ml-auto" @if (!$connected) disabled @endif>
                        {{ __('Continue') }} <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
