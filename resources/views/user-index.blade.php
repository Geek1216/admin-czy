@extends('layouts.panel')

@section('meta')
    <title>{{ __('Users') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Users') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-success ml-auto" href="{{ route('users.create') }}">
                <i class="fas fa-plus mr-1"></i> {{ __('New') }}
            </a>
        </div>
        @livewire('users-top-charts')
        @livewire('user-index')
    </div>
@endsection
