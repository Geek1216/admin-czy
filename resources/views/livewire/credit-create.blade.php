@section('meta')
    <title>{{ __('Credits') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('credits.index') }}">{{ __('Credits') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('credits.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-name text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new credit package.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="credit-title">{{ __('Title') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('title') is-invalid @enderror" id="credit-title" required wire:model="title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="credit-description">{{ __('Description') }}</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="credit-description" wire:model="description"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="credit-price">{{ __('Price') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group @error('price') is-invalid @enderror">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</label>
                                        </div>
                                        <input class="form-control @error('price') is-invalid @enderror" id="credit-price" required wire:model="price">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if (setting('payment_currency', config('fixtures.payment_currency')) !== 'BTC')
                                        <small class="form-text text-muted">
                                            {{ __('It must be in currency\'s lower unit e.g., cents if currency is USD or paisa if currency is INR.') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="credit-value">{{ __('Value') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group @error('value') is-invalid @enderror">
                                        <input class="form-control @error('value') is-invalid @enderror" id="credit-value" required type="number" wire:model="value">
                                        <div class="input-group-append">
                                            <label class="input-group-text">{{ __('Credits') }}</label>
                                        </div>
                                    </div>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('The is the number of credits to be added to user balance.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="credit-order">{{ __('Order') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('order') is-invalid @enderror" id="credit-order" required wire:model="order">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="credit-play-store-product-id">{{ __('Play store ID') }}</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('play_store_product_id') is-invalid @enderror" id="credit-play-store-product-id" wire:model="play_store_product_id">
                                    @error('play_store_product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('If this product must be purchased using Play Store In-App Billing, please enter the related product ID here.') }}
                                    </small>
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
