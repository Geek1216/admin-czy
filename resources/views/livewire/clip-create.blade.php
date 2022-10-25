@section('meta')
    <title>{{ __('Clips') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clips.index') }}">{{ __('Clips') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('clips.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="alert alert-info" readonly="alert">
            {{ __('This feature requires FFmpeg to be installed on server.') }}
            {{ __('Currently, your server is configured to use ":ffmpeg" and ":ffprobe".', ['ffmpeg' => config('laravel-ffmpeg.ffmpeg.binaries'), 'ffprobe' => config('laravel-ffmpeg.ffprobe.binaries')]) }}
            {{ __('Please make sure the binaries are executable.') }}
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-name text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new clip.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-user">{{ __('User') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('user') is-invalid @enderror" id="clip-user" type="number" wire:model="user">
                                    @error('user')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted form-text">
                                        {{ __('This numeric ID of the user account to upload this clip under.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-sections">{{ __('Sections') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('sections') is-invalid @enderror" id="clip-sections" multiple wire:model="sections">
                                        @foreach ($clip_sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sections')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-video">{{ __('Video') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('video') is-invalid @enderror" id="clip-video" type="file" required wire:model="video">
                                        <label class="custom-file-label" for="clip-video">
                                            @if ($video)
                                                {{ 'temporary.' . $video->extension() }}
                                            @else
                                                {{ __('Choose file') }}&hellip;
                                            @endif
                                        </label>
                                        @error('video')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted form-text" wire:loading wire:target="video">
                                        {{ __('Uploading') }}&hellip;
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-description">{{ __('Description') }}</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="clip-description" wire:model="description"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-language">{{ __('Language') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('language') is-invalid @enderror" id="clip-language" required wire:model="language">
                                        @foreach (config('fixtures.languages') as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-duet">{{ __('Duet?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="clip-duet" type="checkbox" wire:model="duet">
                                        <label class="custom-control-label" for="clip-duet">{{ __('Yes') }}</label>
                                    </div>
                                    @error('duet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-private">{{ __('Private?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="clip-private" type="checkbox" wire:model="private">
                                        <label class="custom-control-label" for="clip-private">{{ __('Yes') }}</label>
                                    </div>
                                    @error('private')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="clip-comments">{{ __('Comments?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="clip-comments" type="checkbox" wire:model="comments">
                                        <label class="custom-control-label" for="clip-comments">{{ __('Yes') }}</label>
                                    </div>
                                    @error('comments')
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
