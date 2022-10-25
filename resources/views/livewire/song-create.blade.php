@section('meta')
    <title>{{ __('Songs') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('songs.index') }}">{{ __('Songs') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('songs.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new song.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-sections">{{ __('Sections') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('sections') is-invalid @enderror" id="song-sections" multiple="multiple" wire:model="sections">
                                        @foreach ($song_sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sections')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-title">{{ __('Title') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('title') is-invalid @enderror" id="song-title" required wire:model="title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-artist">{{ __('Artist') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('artist') is-invalid @enderror" id="song-artist" wire:model="artist">
                                    @error('artist')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-album">{{ __('Album / movie') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('album') is-invalid @enderror" id="song-album" wire:model="album">
                                    @error('album')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-audio">{{ __('Main audio') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('audio') is-invalid @enderror" id="song-audio" type="file" required wire:model="audio">
                                        <label class="custom-file-label" for="song-audio">
                                            @if ($audio)
                                                {{ 'temporary.' . $audio->extension() }}
                                            @else
                                                {{ __('Choose file') }}&hellip;
                                            @endif
                                        </label>
                                        @error('audio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted form-text" wire:loading wire:target="audio">
                                        {{ __('Uploading') }}&hellip;
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-cover">{{ __('Cover image') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('cover') is-invalid @enderror" id="song-cover" type="file" wire:model="cover">
                                        <label class="custom-file-label" for="song-cover">
                                            @if ($cover)
                                                {{ 'temporary.' . $cover->extension() }}
                                            @else
                                                {{ __('Choose file') }}&hellip;
                                            @endif
                                        </label>
                                        @error('cover')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted form-text" wire:loading wire:target="cover">
                                        {{ __('Uploading') }}&hellip;
                                    </small>
                                    <small class="text-muted form-text">
                                        {{ __('Ensure longest side on image is at least 256px and no more than 1920px.') }}
                                        {{ __('The image must also be square.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-duration">{{ __('Duration') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group @error('duration') is-invalid @enderror">
                                        <input class="form-control @error('duration') is-invalid @enderror" id="song-duration" wire:model="duration">
                                        <div class="input-group-append">
                                            <span class="input-group-text">{{ __('seconds') }}</span>
                                        </div>
                                    </div>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="song-details">{{ __('Details') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('details') is-invalid @enderror" id="song-details" wire:model="details">
                                    @error('details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('You can put a link to the original content for attribution here.') }}</small>
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
