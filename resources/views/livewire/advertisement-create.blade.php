@section('meta')
    <title>{{ __('Advertisements') }} &raquo; {{ __('New') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('advertisements.index') }}">{{ __('Advertisements') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('advertisements.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Cancel') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="create">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-location text-primary">{{ __('New') }}</h5>
                <p class="card-text">{{ __('Create a new advertisement.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="create">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="advertisement-location">{{ __('Location') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <select autofocus class="form-control @error('location') is-invalid @enderror" id="advertisement-location" required wire:model="location">
                                        @foreach(config('fixtures.advertisement_locations') as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="advertisement-network">{{ __('Network') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('network') is-invalid @enderror" id="advertisement-network" required wire:model="network">
                                        @foreach(config('fixtures.advertisement_networks') as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('network')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="advertisement-type">{{ __('Type') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('type') is-invalid @enderror" id="advertisement-type" required wire:model="type">
                                        @foreach(config('fixtures.advertisement_location_to_types.' . $location) as $code)
                                            <option value="{{ $code }}">{{ config('fixtures.advertisement_types.' . $code) }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @if ($network !== 'custom')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="advertisement-unit">{{ __('Placement / unit') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('unit') is-invalid @enderror" id="advertisement-unit" required wire:model="unit">
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="advertisement-image">{{ __('Image') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <div class="custom-file">
                                            <input class="custom-file-input @error('image') is-invalid @enderror" id="advertisement-image" type="file" required wire:model="image">
                                            <label class="custom-file-label" for="advertisement-image">
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
                                            {{ __('Ensure longest side on image is at least 320px and no more than 1920px.') }}
                                            {{ __('For banner ads, recommended size is 320x50px.') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="advertisement-link">{{ __('Link / URL') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('link') is-invalid @enderror" id="advertisement-link" required wire:model="link">
                                        @error('link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="advertisement-interval">{{ __('Interval') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input class="form-control @error('interval') is-invalid @enderror" id="advertisement-interval" wire:model="interval">
                                        <div class="input-group-append">
                                            <span class="input-group-text">{{ __('Items') }}</span>
                                        </div>
                                        @error('interval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
