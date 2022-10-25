<div class="modal" id="modal-notification" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Notification') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" wire:submit.prevent="send">
                <input type="hidden" name="form" value="notification">
                <div class="modal-body">
                    <p>{{ __('Please provide the notification content you wish to send.') }}</p>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="notification-title">{{ __('Title') }} <span class="text-danger">&ast;</span></label>
                        <div class="col-sm-8">
                            <input class="form-control @error('title') is-invalid @enderror" id="notification-title" name="title" required wire:model="title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-4 col-form-label" for="notification-body">{{ __('Body') }} <span class="text-danger">&ast;</span></label>
                        <div class="col-sm-8">
                            <textarea class="form-control @error('body') is-invalid @enderror" id="notification-body" name="body" required wire:model="body"></textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button class="btn btn-warning">
                        <i class="fas fa-bullhorn mr-1"></i> {{ __('Notify') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
