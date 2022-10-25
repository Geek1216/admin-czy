@extends('layouts.app', [
    'html_class' => 'w-100 h-100',
    'body_class' => 'w-100 h-100 d-flex',
])

@section('meta')
    <title>{{ __('Installation') }} | {{ config('app.name') }}</title>
@endsection

@section('body')
    <div class="container my-auto py-3">
        <div class="row justify-content-center">
            <main class="col-md-10 col-lg-8 col-xl-6">
                <div class="w-100 my-auto">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        <i class="fas fa-check-circle fa-5x text-primary"></i>
                    </div>
                    <h1 class="h3 text-center">{{ __('Installation finished!') }}</h1>
                    <p class="text-center">
                        {{ __('Database and basic setup has been done.') }}
                        {{ __('You should now login to backend and go to Settings for further setup.') }}
                    </p>
                    <p class="text-center">
                        @php
                            $user = App\User::query()->first();
                        @endphp
                        {!! __('To login use <code>:email</code> as <code>12345678</code> as credentials.', ['email' => $user->email]) !!}
                    </p>
                    <div class="btn-toolbar mb-3">
                        <a class="btn btn-dark mx-auto" href="{{ route('settings.update') }}">
                            {{ __('Manage settings') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <p class="text-center text-muted mb-0">
                        {!! __('Redirecting to backend in <span id="timer-seconds">5</span> seconds.') !!}
                    </p>
                </div>
            </main>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var i = 10;
        var timer = setInterval(function () {
            if (i === 0) {
                clearInterval(timer);
                window.location.href = '{{ route('settings.update') }}'
            } else {
                document.getElementById('timer-seconds').innerText = --i + ''
            }
        }, 1000)
    </script>
@endsection
