@section('meta')
    <title>{{ __('Clips') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    @include('partials.boost', ['record' => 'clip'])
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
                <span class="sr-only">{{ __('Loading') }}&hellip;</span>
            </div>
            <h5 class="card-title text-primary">{{ __('Clips') }}</h5>
            <p class="card-text">
                {{ __('List and manage uploaded clips here.') }}
                <a href="" wire:click.prevent="filter()">
                    {{ __($filtering ? 'Hide filters?' : 'Show filters?') }}
                </a>
            </p>
        </div>
        @if ($filtering)
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group mb-lg-0">
                            <label for="filter-search">{{ __('Search') }}</label>
                            <input id="filter-search" class="form-control" placeholder="{{ __('Enter user name or email') }}&hellip;" wire:model.debounce.500ms="search">
                        </div>
                    </div>
                    @php
                        $sections = App\ClipSection::query()->orderBy('order')->get();
                    @endphp
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group mb-lg-0">
                            <label for="filter-section">{{ __('Section') }}</label>
                            <select id="filter-section" class="form-control" wire:model="section">
                                <option value="">{{ __('Any') }}</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group mb-sm-0">
                            <label for="filter-language">{{ __('Language') }}</label>
                            <select id="filter-language" class="form-control" wire:model="language">
                                <option value="">{{ __('Any') }}</option>
                                @foreach (config('fixtures.languages') as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group mb-0">
                            <label for="filter-length">{{ __('Length') }}</label>
                            <select id="filter-length" class="form-control" wire:model="length">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Language') }}</th>
                    <th>{{ __('Approved?') }}</th>
                    <th>
                        @if (($order['created_at'] ?? null) === 'asc')
                            <a class="text-body" href="" wire:click.prevent="sort('created_at', 'desc')">{{ __('Created at') }}</a>
                            <i class="fas fa-sort-amount-down-alt ml-1"></i>
                        @elseif (($order['created_at'] ?? null) === 'desc')
                            <a class="text-body" href="" wire:click.prevent="sort('created_at', false)">{{ __('Created at') }}</a>
                            <i class="fas fa-sort-amount-down ml-1"></i>
                        @else
                            <a class="text-body" href="" wire:click.prevent="sort('created_at', 'asc')">{{ __('Created at') }}</a>
                        @endif
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php
                    $sections = App\ClipSection::query()->orderBy('name')->get();
                @endphp
                @forelse ($clips as $clip)
                    <tr>
                        <td>{{ $clip->id }}</td>
                        <td>
                            <div class="text-hide img-screenshot rounded" style="background-image: url('{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($clip->screenshot) }}')">&nbsp;</div>
                        </td>
                        <td>
                            @if ($clip->description)
                                {{ $clip->description_short }}
                            @else
                                <span class="text-muted">{{ __('Empty') }}</span>
                            @endif
                            @if (isset($links[$clip->id]))
                                <br><a data-behavior="click2copy" href="{{ $links[$clip->id] }}" rel="noopener noreferrer" target="_blank">{{ $links[$clip->id] }}</a>
                            @endif
                        </td>
                        <td>{{ config('fixtures.languages.' . $clip->language) }}</td>
                        <td>
                            @if ($clip->approved)
                                <i class="fas fa-toggle-on mr-1 text-success"></i>
                            @else
                                <i class="fas fa-toggle-off mr-1 text-danger"></i>
                            @endif
                        </td>
                        <td>{{ $clip->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <a class="btn btn-outline-dark btn-sm" href="{{ route('clips.show', $clip) }}">
                                <i class="fas fa-eye mr-1"></i> {{ __('Details') }}
                            </a>
                            <button class="btn btn-dark btn-sm" wire:click="shortlink({{ $clip->id }})" wire:loading.attr="disabled" wire:target="shortlink">
                                <i class="fas fa-link mr-1"></i> {{ __('Link') }}
                            </button>
                            <div class="dropdown d-inline-block">
                                <a aria-expanded="false" aria-haspopup="true" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" href="" role="button">
                                    <i class="fas fa-rocket mr-1"></i> {{ __('Boost') }}
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" wire:click="showBoostDialog({{ $clip->id }}, 'likes')">
                                        <i class="fas fa-heart fa-fw mr-1"></i> {{ __('Likes') }}
                                    </button>
                                    <button class="dropdown-item" wire:click="showBoostDialog({{ $clip->id }}, 'views')">
                                        <i class="fas fa-eye fa-fw mr-1"></i> {{ __('Views') }}
                                    </button>
                                </div>
                            </div>
                            @if ($sections->isNotEmpty())
                                <div class="dropdown d-inline-block">
                                    <a aria-expanded="false" aria-haspopup="true" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" href="" role="button">
                                        <i class="fas fa-plus mr-1"></i> {{ $clip->sections->count() }} {{ __('Sections') }}
                                    </a>
                                    <div class="dropdown-menu">
                                        @foreach ($sections as $section)
                                            @if ($clip->sections->pluck('id')->contains($section->id))
                                                <button class="dropdown-item" wire:click="detach({{ $clip->id }}, {{ $section->id }})">
                                                    <i class="far fa-dot-circle mr-1"></i> {{ $section->name }}
                                                </button>
                                            @else
                                                <button class="dropdown-item" wire:click="attach({{ $clip->id }}, {{ $section->id }})">
                                                    <i class="far fa-circle mr-1"></i> {{ $section->name }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <a class="btn btn-info btn-sm" href="{{ route('clips.update', $clip) }}">
                                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                            </a>
                            @can('administer')
                                <a class="btn btn-danger btn-sm" href="{{ route('clips.destroy', $clip) }}">
                                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-muted" colspan="7">{{ __('Could not find any clips to show.') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($clips->hasPages())
            <div class="card-body border-top">
                {{ $clips->onEachSide(1)->links() }}
            </div>
        @endif
        <div class="card-body border-top">
            {{ __('Showing :from to :to of :total clips.', ['from' => $clips->firstItem() ?: 0, 'to' => $clips->lastItem() ?: 0, 'total' => $clips->total()]) }}
        </div>
    </div>
</div>
