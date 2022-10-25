@if ($boost)
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Boost') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}" wire:click="hideBoostDialog()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ __('You have chosen to boost (or seed) :type for this :record.', ['type' => $boostType, 'record' => $record]) }}
                        {{ __('Please enter how many and click "Boost" button below.') }}
                    </p>
                    <div class="form-group row mb-0">
                        <label class="col-form-label col-sm-3" for="boost-count">{{ __('Count') }}</label>
                        <div class="col-sm-9">
                            <input class="form-control @error('boostCount') is-invalid @enderror" id="boost-count" name="count" type="number" wire:model.debounce.500ms="boostCount">
                            @error('boostCount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-dark" wire:click="hideBoostDialog()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:loading.attr="disabled" wire:click="submitBoost()">
                        <i class="fas fa-rocket mr-1"></i> {{ __('Boost') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
@endif
