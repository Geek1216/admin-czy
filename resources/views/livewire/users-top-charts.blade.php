<div>
    <div class="btn-toolbar justify-content-center mb-3">
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-link" wire:click="update('1H')" @if ($mode === '1H') disabled @endif wire:loading.attr="disabled">{{ __('1H') }}</button>
            <button type="button" class="btn btn-link" wire:click="update('1D')" @if ($mode === '1D') disabled @endif wire:loading.attr="disabled">{{ __('1D') }}</button>
            <button type="button" class="btn btn-link" wire:click="update('1W')" @if ($mode === '1W') disabled @endif wire:loading.attr="disabled">{{ __('1W') }}</button>
            <button type="button" class="btn btn-link" wire:click="update('1M')" @if ($mode === '1M') disabled @endif wire:loading.attr="disabled">{{ __('1M') }}</button>
        </div>
    </div>
    <div class="row">
        @foreach (['followed', 'viewed', 'liked', 'saved', 'commented', 'uploaded'] as $type)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        @if ($results[$type])
                            <a class="float-right text-decoration-none stretched-link" href="{{ route('users.show', $results[$type]) }}">
                                <i class="fas fa-link"></i>
                            </a>
                        @endif
                        <strong class="card-title">{{ __('Most '.$type) }}</strong>
                        <p class="card-text">
                            @if ($results[$type])
                                {{ __('#:id by @:username', ['id' => $results[$type]->id, 'username' => $results[$type]->username]) }}
                            @else
                                {{ __('n/a') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
