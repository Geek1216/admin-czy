@section('meta')
    <title>{{ __('Notification schedules') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notification-schedules.index') }}">{{ __('Notification schedules') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('notification-schedules.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" clip="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new notification schedule.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="notification-schedule-time">{{ __('Time') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autocomplete="off" autofocus class="form-control @error('time') is-invalid @enderror" data-widget="timepicker" id="notification-schedule-time" required wire:model="time">
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row" wire:ignore>
                                <label class="col-sm-4 col-form-label" for="notification-schedule-template">{{ __('Template') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('template') is-invalid @enderror" id="notification-schedule-template" wire:model="template">
                                        <option value="">{{ __('None') }}</option>
                                        @foreach ($templates as $row)
                                            <option value="{{ $row->id }}">{{ $row->title_short }}</option>
                                        @endforeach
                                    </select>
                                    @error('template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="notification-schedule-clip">{{ __('Clip') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('clip') is-invalid @enderror" id="notification-schedule-clip" wire:model="clip">
                                        <option value="">{{ __('None') }}</option>
                                        @foreach (config('fixtures.notification_schedule_clips') as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('clip')
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
