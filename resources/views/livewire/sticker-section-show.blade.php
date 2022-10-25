@section('meta')
    <title>{{ __('Sticker sections') }} &raquo; {{ $section->name }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sticker-sections.index') }}">{{ __('Sticker sections') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $section->name }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('sticker-sections.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Sticker sections') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('sticker-sections.update', $section) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('sticker-sections.destroy', $section) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing sticker section here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Name') }}</th>
                                <td class="w-100">{{ $section->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Sort order') }}</th>
                                <td class="w-100">{{ $section->order }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Stickers') }}</th>
                                <td class="w-100">{{ $section->stickers()->count() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $section->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $section->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Stickers') }}</h5>
                        <p class="card-text">{{ __('Manage stickers available this section.') }}</p>
                    </div>
                    <div class="card-body border-top">
                        <form class="mb-0" wire:submit.prevent="upload">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="sticker-images">{{ __('Images') }} <span class="text-danger">&ast;</span></label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('images') is-invalid @enderror" id="sticker-images" multiple type="file" required wire:model="images">
                                        <label class="custom-file-label" for="sticker-images">
                                            @if ($images)
                                                {{ __(':count image(s)', ['count' => count($images)]) }}
                                            @else
                                                {{ __('Choose file') }}&hellip;
                                            @endif
                                        </label>
                                        @error('images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted form-text" wire:loading wire:target="image">
                                        {{ __('Uploading') }}&hellip;
                                    </small>
                                    <small class="text-muted form-text">
                                        {{ __('Ensure the image is at least 256x256px and no more than 1024x1024px in size.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4">
                                    <button class="btn btn-success">
                                        <i class="fas fa-upload mr-1"></i> {{ __('Upload') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body border-top pb-0">
                        <div class="row">
                            @forelse ($section->stickers as $sticker)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <img alt="#{{ $sticker->id }}" class="img-thumbnail @cannot('administer') mb-3 @endcannot" src="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($sticker->image) }}">
                                    @can('administer')
                                        <div class="btn-toolbar m-3">
                                            <a class="btn btn-outline-danger btn-sm mx-auto" href="{{ route('stickers.destroy', $sticker) }}">
                                                <i class="fas fa-trash-alt mr-1"></i> {{ __('Delete') }}
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                            @empty
                                <div class="col">
                                    <p class="text-muted text-center">
                                        {{ __('No stickers to display.') }}
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                @include('partials.activity_logs')
            </div>
        </div>
    </div>
</div>
