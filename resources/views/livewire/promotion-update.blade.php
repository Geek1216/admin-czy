@section('meta')
    <title>{{ __('Promotions') }} &raquo; {{ $promotion->title }} &raquo; {{ __('Edit') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}">{{ __('Promotions') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('promotions.show', $promotion) }}">{{ $promotion->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('promotions.show', $promotion) }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Details') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="update">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Edit') }}</h5>
                <p class="card-text">{{ __('Update existing promotion information here.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="update">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="promotion-title">{{ __('Title') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('title') is-invalid @enderror" id="promotion-title" required wire:model="title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="promotion-description">{{ __('Description') }}</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="promotion-description" wire:model="description"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="promotion-image">{{ __('Image') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('image') is-invalid @enderror" id="promotion-image" type="file" wire:model="image">
                                        <label class="custom-file-label" for="promotion-image">
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
                                        {{ __('Ensure longest side on image is at least 512px and no more than 1920px.') }}
                                        {{ __('Please keep all promotion banners the same size for best in-app experience.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="promotion-sticky">{{ __('Sticky?') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch mt-sm-2">
                                        <input class="custom-control-input" id="promotion-sticky" type="checkbox" wire:model="sticky">
                                        <label class="custom-control-label" for="promotion-sticky">{{ __('Yes') }}</label>
                                    </div>
                                    @error('sticky')
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
