@section('meta')
    <title>{{ __('Redemptions') }} &raquo; #{{ $redemption->id }} &raquo; {{ __('Delete') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('redemptions.index') }}">{{ __('Redemptions') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('redemptions.show', $redemption) }}">#{{ $redemption->id }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Delete') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('redemptions.show', $redemption) }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Details') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="destroy">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Delete') }}</h5>
                <p class="card-text">{{ __('Removing this redemption is permanent and cannot be undone. Are you sure?') }}</p>
            </div>
            <div class="card-body border-top">
                <form class="mb-0" wire:submit.prevent="destroy">
                    <button class="btn btn-danger" wire:click="destroy">
                        <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
