@section('meta')
    <title>{{ __('Items') }} &raquo; {{ $item->name }} &raquo; {{ __('Edit') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.index') }}">{{ __('Items') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.show', $item) }}">{{ $item->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('items.show', $item) }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Details') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="update">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Edit') }}</h5>
                <p class="card-text">{{ __('Update existing item information here.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="update">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="item-name">{{ __('Name') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('name') is-invalid @enderror" id="item-name" required wire:model="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="item-image">{{ __('Image') }}</label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('image') is-invalid @enderror" id="item-image" type="file" wire:model="image">
                                        <label class="custom-file-label" for="item-image">
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
                                        {{ __('Ensure longest side on image is at least 128px and no more than 1024px.') }}
                                        {{ __('The image must also be square.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="item-price">{{ __('Price') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group @error('price') is-invalid @enderror">
                                        <input class="form-control @error('price') is-invalid @enderror" id="item-price" required type="number" wire:model="price">
                                        <div class="input-group-append">
                                            <label class="input-group-text">{{ __('Credits') }}</label>
                                        </div>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('The price is how many credits it would require to buy this item.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="item-value">{{ __('Value') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group @error('value') is-invalid @enderror">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</label>
                                        </div>
                                        <input class="form-control @error('value') is-invalid @enderror" id="item-value" required wire:model="value">
                                    </div>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('This is the value of this item when redeemed.') }}
                                        @if (setting('payment_currency', config('fixtures.payment_currency')) !== 'BTC')
                                            {{ __('It must be in currency\'s lower unit e.g., cents if currency is USD or paisa if currency is INR.') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="item-minimum">{{ __('Minimum') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('minimum') is-invalid @enderror" id="item-minimum" required type="number" wire:model="minimum">
                                    @error('minimum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('This is the minimum no. this user must have to redeem.') }}</small>
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
