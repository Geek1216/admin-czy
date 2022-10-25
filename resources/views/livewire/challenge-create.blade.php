@section('meta')
    <title>{{ __('Challenges') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}">{{ __('Challenges') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('challenges.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-name text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new challenge.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="challenge-hashtag">{{ __('Hashtag') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">#</span>
                                        </div>
                                        <input autofocus class="form-control @error('hashtag') is-invalid @enderror" id="challenge-hashtag" required wire:model="hashtag">
                                        @error('hashtag')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="challenge-image">{{ __('Image') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('image') is-invalid @enderror" id="challenge-image" type="file" required wire:model="image">
                                        <label class="custom-file-label" for="challenge-image">
                                            @if ($image)
                                                {{ 'temporary.' . $image->extension() }}
                                            @else
                                                {{ __('Choose file') }}&hellip;
                                            @endif
                                        </label>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted form-text" wire:loading wire:target="image">
                                        {{ __('Uploading') }}&hellip;
                                    </small>
                                    <small class="text-muted form-text">
                                        {{ __('Ensure longest side on image is at least 256px and no more than 1920px.') }}
                                        {{ __('The image resolution must be in 16:9 ratio e.g., 640x360, 120x720, 1920x1080 etc.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="challenge-description">{{ __('Description') }}</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="challenge-description" wire:model="description"></textarea>
                                    @error('description')
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
