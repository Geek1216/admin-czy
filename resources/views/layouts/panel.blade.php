@extends('layouts.app')

@section('styles')
    @parent
    <link rel="stylesheet" href="https://admin.cozzy.in/css/flatpickr.css">
    @livewireStyles
@endsection

@section('body')
    <header class="sticky-top mb-3">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main" aria-controls="navbar-main" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar-main">
                <ul class="navbar-nav">
                    <li class="nav-item @if (Route::is('home')) active @endif">
                        <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-solar-panel fa-fw mr-1"></i> {{ __('Home') }}</a>
                    </li>
                    <li class="nav-item @if (Route::is('users.*')) active @endif">
                        <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-user-friends fa-fw mr-1"></i> {{ __('Users') }}</a>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('videos/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('clips.index') }}" id="dropdown-videos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-film fa-fw mr-1"></i> {{ __('Videos') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-videos">
                            <a class="dropdown-item @if (Route::is('clips.*')) active @endif" href="{{ route('clips.index') }}">{{ __('Clips') }}</a>
                            <a class="dropdown-item @if (Route::is('clip-sections.*')) active @endif" href="{{ route('clip-sections.index') }}">{{ __('Sections') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('news/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('articles.index') }}" id="dropdown-news" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-newspaper fa-fw mr-1"></i> {{ __('News') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-news">
                            <a class="dropdown-item @if (Route::is('articles.*')) active @endif" href="{{ route('articles.index') }}">{{ __('Articles') }}</a>
                            <a class="dropdown-item @if (Route::is('article-sections.*')) active @endif" href="{{ route('article-sections.index') }}">{{ __('Sections') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('music/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('songs.index') }}" id="dropdown-music" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-music fa-fw mr-1"></i> {{ __('Music') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-music">
                            <a class="dropdown-item @if (Route::is('songs.*')) active @endif" href="{{ route('songs.index') }}">{{ __('Songs') }}</a>
                            <a class="dropdown-item @if (Route::is('song-sections.*')) active @endif" href="{{ route('song-sections.index') }}">{{ __('Sections') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('engagement/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('comments.index') }}" id="dropdown-engagement" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-quote-right fa-fw mr-1"></i> {{ __('Engagement') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-engagement">
                            <a class="dropdown-item @if (Route::is('suggestions.*')) active @endif" href="{{ route('suggestions.index') }}">{{ __('Suggestions') }}</a>
                            <a class="dropdown-item @if (Route::is('challenges.*')) active @endif" href="{{ route('challenges.index') }}">{{ __('Challenges') }}</a>
                            <a class="dropdown-item @if (Route::is('sticker-sections.*')) active @endif" href="{{ route('sticker-sections.index') }}">{{ __('Stickers') }}</a>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">{{ __('Responses') }}</h6>
                            <a class="dropdown-item @if (Route::is('comments.*')) active @endif" href="{{ route('comments.index') }}">{{ __('Comments') }}</a>
                            <a class="dropdown-item @if (Route::is('reports.*')) active @endif" href="{{ route('reports.index') }}">{{ __('Reports') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('rewards/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('levels.index') }}" id="dropdown-rewards" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-trophy fa-fw mr-1"></i> {{ __('Rewards') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-rewards">
                            <a class="dropdown-item @if (Route::is('levels.*')) active @endif" href="{{ route('levels.index') }}">{{ __('Levels') }}</a>
                            <a class="dropdown-item @if (Route::is('verifications.*')) active @endif" href="{{ route('verifications.index') }}">{{ __('Verifications') }}</a>
                            
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">{{ __('Gifts') }}</h6>
                                <a class="dropdown-item @if (Route::is('credits.*')) active @endif" href="{{ route('credits.index') }}">{{ __('Credits') }}</a>
                                <a class="dropdown-item @if (Route::is('items.*')) active @endif" href="{{ route('items.index') }}">{{ __('Items') }}</a>
                                <a class="dropdown-item @if (Route::is('payments.*')) active @endif" href="{{ route('payments.index') }}">{{ __('Payments') }}</a>
                                <a class="dropdown-item @if (Route::is('redemptions.*')) active @endif" href="{{ route('redemptions.index') }}">{{ __('Redemptions') }}</a>
                            
                        </div>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('marketing/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('advertisements.index') }}" id="dropdown-marketing" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bullhorn fa-fw mr-1"></i> {{ __('Marketing') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-marketing">
                            <a class="dropdown-item @if (Route::is('advertisements.*')) active @endif" href="{{ route('advertisements.index') }}">{{ __('Advertisements') }}</a>
                            <a class="dropdown-item @if (Route::is('promotions.*')) active @endif" href="{{ route('promotions.index') }}">{{ __('Promotions') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown @if (Request::is('notifications/*')) active @endif">
                        <a class="nav-link dropdown-toggle" href="{{ route('notification-schedules.index') }}" id="dropdown-notifications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw mr-1"></i> {{ trim(__('_Notifications'), '_') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-notifications">
                            <a class="dropdown-item @if (Route::is('notification-schedules.*')) active @endif" href="{{ route('notification-schedules.index') }}">{{ __('Schedules') }}</a>
                            <a class="dropdown-item @if (Route::is('notification-templates.*')) active @endif" href="{{ route('notification-templates.index') }}">{{ __('Templates') }}</a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    @can('administer')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('settings.update') }}" id="dropdown-settings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog fa-fw mr-1"></i> {{ __('Manage') }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-settings">
                                <a class="dropdown-item" href="{{ route('settings.update') }}">{{ __('Settings') }}</a>
                                <a class="dropdown-item" href="{{ route('dotenv.update') }}">{{ __('.env File') }}</a>
                            </div>
                        </li>
                    @endcan
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ route('profile') }}" id="dropdown-account" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-cog fa-fw mr-1"></i> {{ __('Account') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-account">
                            <a class="dropdown-item" href="{{ route('profile') }}">{{ __('Profile') }}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="logout(event)">{{ __('Logout') }}</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        @include('flash::message')
    </div>
    @yield('content')
    <footer class="my-3">
        <div class="container">
            <p class="mb-0">
                <strong>{{ config('app.name') }}</strong> &copy; {{ date('Y') }}
            </p>
        </div>
    </footer>
@endsection

@section('scripts')
    @parent
    @livewireScripts
@endsection
