@extends('layouts.panel')

@section('meta')
    <title>{{ __('Home') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

@section('body')
    @livewire('send-notification')
    @parent
@endsection

@section('content')
    @if (session('status'))
        <div class="container">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Home') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <button class="btn btn-warning ml-auto" data-toggle="modal" data-target="#modal-notification">
                <i class="fas fa-bullhorn mr-1"></i> {{ __('Notification') }}
            </button>
        </div>
        @if (config('fixtures.dashboard_statistics'))
            <div class="row">
                <div class="col-xl-8">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ __('Views') }}</h5>
                            <p class="card-text">{{ __('Summary of views in last 24 hours.') }}</p>
                        </div>
                        <div class="card-body border-top">
                            <canvas class="w-100" data-chart="line" data-dataset='{!! json_encode($views) !!}' data-label="# of views" style="height: 250px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ __('Platforms') }}</h5>
                            <p class="card-text">{{ __('Platform diversity among users.') }}</p>
                        </div>
                        <div class="card-body border-top">
                            <canvas class="w-100" data-chart="pie" data-dataset='{!! json_encode($devices) !!}' data-label="# of views" style="height: 250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="mb-3 mb-lg-0">
                        @livewire('statistics-users')
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="mb-3 mb-lg-0">
                        @livewire('statistics-clips')
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    @livewire('statistics-views')
                </div>
            </div>
        @else
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    {{ __('You have enabled high-performance dashboard so no statistics are shown.') }}
                </div>
            </div>
        @endif
    </div>
@endsection
