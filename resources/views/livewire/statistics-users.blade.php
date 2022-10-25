<div class="card shadow-sm">
    <div class="card-body">
        <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
            <span class="sr-only">{{ __('Loading') }}&hellip;</span>
        </div>
        <h5 class="card-title text-primary">{{ __('Users') }}</h5>
        <p class="card-text">{{ __('Below are the number of registrations shown comparatively.') }}</p>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <tbody>
            <tr>
                <th class="bg-light w-50">
                    @if ($mode === '1H')
                        {{ __('This hour') }}
                    @elseif ($mode === '1D')
                        {{ __('Today') }}
                    @elseif ($mode === '1W')
                        {{ __('This week') }}
                    @elseif ($mode === '1M')
                        {{ __('This month') }}
                    @endif
                </th>
                <td class="w-50">{{ $current }}</td>
            </tr>
            <tr>
                <th class="bg-light w-50">
                    @if ($mode === '1H')
                        {{ __('Last hour') }}
                    @elseif ($mode === '1D')
                        {{ __('Yesterday') }}
                    @elseif ($mode === '1W')
                        {{ __('Last week') }}
                    @elseif ($mode === '1M')
                        {{ __('Last month') }}
                    @endif
                </th>
                <td class="w-50">{{ $previous }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body border-top">
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-dark @if ($mode === '1H') active @endif" wire:click="update('1H')" wire:loading.attr="disabled">{{ __('1H') }}</button>
            <button type="button" class="btn btn-outline-dark @if ($mode === '1D') active @endif" wire:click="update('1D')" wire:loading.attr="disabled">{{ __('1D') }}</button>
            <button type="button" class="btn btn-outline-dark @if ($mode === '1W') active @endif" wire:click="update('1W')" wire:loading.attr="disabled">{{ __('1W') }}</button>
            <button type="button" class="btn btn-outline-dark @if ($mode === '1M') active @endif" wire:click="update('1M')" wire:loading.attr="disabled">{{ __('1M') }}</button>
        </div>
    </div>
    <div class="card-footer">
        <small>
            <span class="text-muted">{{ __('Last updated on') }}</span> {{ now()->format('H:i') }}
        </small>
    </div>
</div>
