@section('meta')
    <title>{{ __('Notification templates') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notification-templates.index') }}">{{ __('Notification templates') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('notification-templates.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new notification template.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="notification-template-title">{{ __('Title') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('title') is-invalid @enderror" id="notification-template-title" required wire:model="title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('To insert emojis') }}, <a href="https://www.emojicopy.com/" rel="noopener noreferrer" target="_blank">{{ __('you may copy paste from here') }}</a>.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="notification-template-body">{{ __('Body') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <textarea class="form-control @error('body') is-invalid @enderror" id="notification-template-body" required wire:model="body"></textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('To insert emojis') }}, <a href="https://www.emojicopy.com/" rel="noopener noreferrer" target="_blank">{{ __('you may copy paste from here') }}</a>.</small>
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
