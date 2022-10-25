@section('meta')
    <title>{{ __('Articles') }} &raquo; {{ $article->title_short }} &raquo; {{ __('Edit') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">{{ __('Articles') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">{{ $article->title_short }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('articles.show', $article) }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Details') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="update">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Edit') }}</h5>
                <p class="card-text">{{ __('Update existing article information here.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="update">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-sections">{{ __('Sections') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('sections') is-invalid @enderror" id="article-sections" multiple wire:model="sections">
                                        @foreach ($article_sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sections')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-title">{{ __('Title') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('title') is-invalid @enderror" id="article-title" required wire:model="title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-snippet">{{ __('Snippet') }}</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control @error('snippet') is-invalid @enderror" id="article-snippet" wire:model="snippet"></textarea>
                                    @error('snippet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-image">{{ __('Image') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('image') is-invalid @enderror" id="article-image" wire:model="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-link">{{ __('Link / URL') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('link') is-invalid @enderror" id="article-link" required wire:model="link">
                                    @error('link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-source">{{ __('Source') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('source') is-invalid @enderror" id="article-source" wire:model="source">
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-published_at">{{ __('Published at') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('published_at') is-invalid @enderror" data-widget="datetimepicker" id="article-published_at" required wire:model="published_at">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4">
                                    <button class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i> {{ __('Update') }}
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
