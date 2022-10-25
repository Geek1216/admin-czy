@section('meta')
    <title>{{ __('Article sections') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('article-sections.index') }}">{{ __('Article sections') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('article-sections.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new article section.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-section-name">{{ __('Name') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('name') is-invalid @enderror" id="article-section-name" required wire:model="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-section-google-news-topic">{{ __('Google news topic') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('google_news_topic') is-invalid @enderror" id="article-section-google-news-topic" wire:model="google_news_topic">
                                    @error('google_news_topic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('To find desired topics') }}, <a href="https://news.google.com/" rel="noopener noreferrer" target="_blank">{{ __('please check here') }}</a>.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-section-google-news-language">{{ __('Google news language') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('google_news_language') is-invalid @enderror" id="article-section-google-news-language" wire:model="google_news_language">
                                    @error('google_news_language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('This must be 2 character language code supported by Google News e.g., hi for Hindi or en for English.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="article-section-order">{{ __('Sort order') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('order') is-invalid @enderror" id="article-section-order" required type="number" wire:model="order">
                                    @error('order')
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
