@section('meta')
    <title>{{ __('Articles') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Articles') }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-success ml-auto" href="{{ route('articles.create') }}">
                <i class="fas fa-plus mr-1"></i> {{ __('New') }}
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading>
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Articles') }}</h5>
                <p class="card-text">
                    {{ __('List and manage registered articles here.') }}
                    <a href="" wire:click.prevent="filter()">
                        {{ __($filtering ? 'Hide filters?' : 'Show filters?') }}
                    </a>
                </p>
            </div>
            @if ($filtering)
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group mb-md-0">
                                <label for="filter-search">{{ __('Search') }}</label>
                                <input id="filter-search" class="form-control" placeholder="{{ __('Enter title or source') }}&hellip;" wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        @php
                            $sections = App\ArticleSection::query()->orderBy('order')->get();
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group mb-md-0">
                                <label for="filter-section">{{ __('Section') }}</label>
                                <select id="filter-section" class="form-control" wire:model="section">
                                    <option value="">{{ __('Any') }}</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 offset-lg-3">
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
                        <th>{{ __('Source') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Link') }}</th>
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
                    @forelse ($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>
                                @if ($article->image)
                                    <div class="text-hide img-screenshot rounded" style="background-image: url('{{ $article->image }}')">&nbsp;</div>
                                @endif
                            </td>
                            <td>
                                @if ($article->source)
                                    {{ $article->source }}
                                @else
                                    <span class="text-muted">{{ __('Empty') }}</span>
                                @endif
                            </td>
                            <td>{{ $article->title_short }}</td>
                            <td><a href="{{ $article->link }}" rel="noopener noreferrer" target="_blank">{{ $article->link_short }}</a></td>
                            <td>{{ $article->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <a class="btn btn-outline-dark btn-sm" href="{{ route('articles.show', $article) }}">
                                    <i class="fas fa-eye mr-1"></i> {{ __('Details') }}
                                </a>
                                <a class="btn btn-info btn-sm" href="{{ route('articles.update', $article) }}">
                                    <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
                                </a>
                                @can('administer')
                                    <a class="btn btn-danger btn-sm" href="{{ route('articles.destroy', $article) }}">
                                        <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted" colspan="7">{{ __('Could not find any articles to show.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if ($articles->hasPages())
                <div class="card-body border-top">
                    {{ $articles->onEachSide(1)->links() }}
                </div>
            @endif
            <div class="card-body border-top">
                {{ __('Showing :from to :to of :total articles.', ['from' => $articles->firstItem() ?: 0, 'to' => $articles->lastItem() ?: 0, 'total' => $articles->total()]) }}
            </div>
        </div>
    </div>
</div>
