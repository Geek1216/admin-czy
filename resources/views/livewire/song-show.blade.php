@section('meta')
    <title>{{ __('Songs') }} &raquo; {{ $song->title }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('songs.index') }}">{{ __('Songs') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $song->title }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('songs.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Songs') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('songs.update', $song) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('songs.destroy', $song) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing song here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Sections') }}</th>
                                <td class="w-100">
                                    @forelse ($song->sections as $section)
                                        <span class="badge badge-light">{{ $section->name }}</span>
                                    @empty
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Title') }}</th>
                                <td class="w-100">{{ $song->title }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Artist') }}</th>
                                <td class="w-100">
                                    @if ($song->artist)
                                        {{ $song->artist }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Album / movie') }}</th>
                                <td class="w-100">
                                    @if ($song->album)
                                        {{ $song->album }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Main audio') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($song->audio) }}" rel="noopener noreferrer" target="_blank">{{ $song->audio }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Cover image') }}</th>
                                <td class="w-100">
                                    @if ($song->cover)
                                        <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($song->cover) }}" rel="noopener noreferrer" target="_blank">{{ $song->cover }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Duration') }}</th>
                                <td class="w-100">{{ $song->duration }} {{ __('seconds') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Details') }}</th>
                                <td class="w-100">
                                    @if ($song->details)
                                        <a href="{{ $song->details }}" rel="noopener noreferrer" target="_blank">{{ $song->details }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Clips') }}</th>
                                <td class="w-100">{{ $song->clips()->count() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $song->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $song->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                @include('partials.activity_logs')
            </div>
        </div>
    </div>
</div>
