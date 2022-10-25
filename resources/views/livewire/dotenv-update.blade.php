@section('meta')
    <title>{{ __('.env File') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('.env File') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <button class="btn btn-secondary" wire:click="cacheConfig" wire:loading.attr="disabled">
                <i class="fas fa-bolt mr-1"></i> {{ __('Cache config') }}
            </button>
            @if (config('queue.default') !== 'sync')
                <button class="btn btn-warning ml-1" wire:click="restartQueue" wire:loading.attr="disabled">
                    <i class="fas fa-redo mr-1"></i> {{ __('Restart queue') }}
                </button>
            @endif
            <button class="btn btn-danger ml-1" wire:click="purgeCache" wire:loading.attr="disabled">
                <i class="fas fa-broom mr-1"></i> {{ __('Purge cache') }}
            </button>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="update">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Configuration') }}</h5>
                <p class="card-text">{{ __('Update .env file contents. Please bear in mind, any misconfiguration here can prevent application from running at all.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="update">
                            <div class="form-group row" wire:ignore>
                                <label class="col-sm-4 col-form-label" for="config-env">{{ __('Environment') }}</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control text-monospace text-nowrap @error('env') is-invalid @enderror" id="config-env" wire:model="env" rows="25" style="font-size: .75em"></textarea>
                                    @error('env')
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
