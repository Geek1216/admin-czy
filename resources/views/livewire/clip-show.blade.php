@section('meta')
    <title>{{ __('Clips') }} &raquo; #{{ $clip->id }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

@section('body')
    @livewire('send-notification', ['clip' => $clip->id])
    @parent
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clips.index') }}">{{ __('Clips') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $clip->id }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('clips.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Clips') }}
            </a>
            <button class="btn btn-primary ml-auto" wire:click="shortlink" wire:loading.attr="disabled" wire:target="shortlink">
                <i class="fas fa-link mr-1"></i> {{ __('Link') }}
            </button>
            <button class="btn btn-warning ml-1" data-toggle="modal" data-target="#modal-notification">
                <i class="fas fa-bullhorn mr-1"></i> {{ __('Notification') }}
            </button>
            <a class="btn btn-info ml-1" href="{{ route('clips.update', $clip) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('clips.destroy', $clip) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing clip here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $clip->user) }}">{{ $clip->user->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Song') }}</th>
                                <td class="w-100">
                                    @if ($clip->song)
                                        <a href="{{ route('songs.show', $clip->song) }}">{{ $clip->song->title }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Sections') }}</th>
                                <td class="w-100">
                                    @forelse ($clip->sections as $section)
                                        <span class="badge badge-light">{{ $section->name }}</span>
                                    @empty
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Description') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($clip->description)
                                        {{ $clip->description }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($link)
                                <tr>
                                    <th class="bg-light">{{ __('Shortlink') }}</th>
                                    <td class="w-100">
                                        <a data-behavior="click2copy" href="{{ $link }}" rel="noopener noreferrer" target="_blank">{{ $link }}</a>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th class="bg-light">{{ __('Video') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($clip->video) }}" rel="noopener noreferrer" target="_blank">{{ $clip->video }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Screenshot') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($clip->screenshot) }}" rel="noopener noreferrer" target="_blank">{{ $clip->screenshot }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Preview') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($clip->preview) }}" rel="noopener noreferrer" target="_blank">{{ $clip->preview }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Language') }}</th>
                                <td class="w-100">{{ config('fixtures.languages.' . $clip->language) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Private?') }}</th>
                                <td class="w-100">
                                    @if ($clip->private)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Duet?') }}</th>
                                <td class="w-100">
                                    @if ($clip->duet)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    @if ($clip->comments)
                                        {{ __('Comments') }}
                                    @else
                                        {{ __('Comments?') }}
                                    @endif
                                </th>
                                <td class="w-100">
                                    @if ($clip->comments)
                                        {{ $clip->comments()->count() }}
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Approved?') }}</th>
                                <td class="w-100">
                                    @if ($clip->approved)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            @if ($clip->user->business)
                                <tr>
                                    <th class="bg-light align-text-top">{{ __('Call to action') }}</th>
                                    <td class="w-100 text-wrap">
                                        @if ($clip->cta_label)
                                            <a href="{{ $clip->cta_link }}" rel="noopener noreferrer" target="_blank">
                                                {{ config('fixtures.call_to_actions.' . $clip->cta_label) }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ __('None') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if ($clip->location)
                                <tr>
                                    <th class="bg-light">{{ __('Location') }}</th>
                                    <td class="w-100">
                                        <a href="http://maps.google.com/maps?q={{ $clip->latitude }},{{ $clip->longitude }}" rel="noopener noreferrer" target="_blank">
                                            {{ $clip->location }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th class="bg-light">{{ __('Views') }}</th>
                                <td class="w-100">{{ views($clip)->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Duration') }}</th>
                                <td class="w-100">{{ $clip->duration }} {{ __('seconds') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Likes') }}</th>
                                <td class="w-100">{{ $clip->likes()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Favorites') }}</th>
                                <td class="w-100">{{ $clip->favorites()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Reports') }}</th>
                                <td class="w-100">{{ $clip->reports()->count() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $clip->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $clip->updated_at->format('d/m/Y H:i') }}
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
