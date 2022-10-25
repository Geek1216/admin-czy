@section('meta')
    <title>{{ __('Levels') }} &raquo; {{ $level->name }} &raquo; {{ __('Edit') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('levels.index') }}">{{ __('Levels') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('levels.show', $level) }}">{{ $level->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('levels.show', $level) }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Details') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="update">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Edit') }}</h5>
                <p class="card-text">{{ __('Update existing level information here.') }}</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="update">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-name">{{ __('Name') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input autofocus class="form-control @error('name') is-invalid @enderror" id="level-name" required wire:model="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-color">{{ __('Color') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">#</span>
                                        </div>
                                        <input class="form-control @error('color') is-invalid @enderror" id="level-color" required wire:model="color">
                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        {{ __('Please input hexadecimal code for a color of your choice e.g., 000000 for black.') }}
                                        {!! __('To find out and copy popular colors, :link_preclick here:link_post.', ['link_pre' => '<a href="https://brandcolors.net/" target="_blank">', 'link_post' => '</a>']) !!}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-order">{{ __('Order') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('order') is-invalid @enderror" id="level-order" required type="number" wire:model="order">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-followers">{{ __('Followers') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('followers') is-invalid @enderror" id="level-followers" required type="number" wire:model="followers">
                                    @error('followers')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('If you would like to exclude this field from check criteria, use -1 as value.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-uploads">{{ __('Uploads') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('uploads') is-invalid @enderror" id="level-uploads" required type="number" wire:model="uploads">
                                    @error('uploads')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('If you would like to exclude this field from check criteria, use -1 as value.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-views">{{ __('Views') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('views') is-invalid @enderror" id="level-views" required type="number" wire:model="views">
                                    @error('views')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('If you would like to exclude this field from check criteria, use -1 as value.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-likes">{{ __('Likes') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('likes') is-invalid @enderror" id="level-likes" required type="number" wire:model="likes">
                                    @error('likes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('If you would like to exclude this field from check criteria, use -1 as value.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="level-reward">{{ __('Reward') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group @error('reward') is-invalid @enderror">
                                        <input class="form-control @error('reward') is-invalid @enderror" id="level-reward" required type="number" wire:model="reward">
                                        <div class="input-group-append">
                                            <label class="input-group-text">{{ __('Credits') }}</label>
                                        </div>
                                    </div>
                                    @error('reward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('Upon reaching this level, this value will be added to the user\'s balance.') }}
                                    </small>
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
