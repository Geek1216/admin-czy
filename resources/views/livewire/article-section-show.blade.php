@section('meta')
    <title>{{ __('Article sections') }} &raquo; {{ $section->name }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('article-sections.index') }}">{{ __('Article sections') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $section->name }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('article-sections.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Article sections') }}
            </a>
            <button class="btn btn-warning ml-auto" wire:click="crawl({{ $section->id }})" wire:loading.attr="disabled">
                <i class="fas fa-spider mr-1"></i> {{ __('Crawl') }}
            </button>
            <a class="btn btn-info ml-1" href="{{ route('article-sections.update', $section) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('article-sections.destroy', $section) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing article section here.') }}</p>
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
                                <th class="bg-light">{{ __('Google news topic') }}</th>
                                <td class="w-100">
                                    @if (empty($section->google_news_topic))
                                        <span class="text-muted">{{ __('Not set') }}</span>
                                    @else
                                        <a href="https://news.google.com/topics/{{ $section->google_news_topic }}" rel="noopener noreferrer" target="_blank">{{ $section->google_news_topic }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Google news language') }}</th>
                                <td class="w-100">
                                    @if (empty($section->google_news_language))
                                        <span class="text-muted">{{ __('Not set') }}</span>
                                    @else
                                        {{ $section->google_news_language }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Articles') }}</th>
                                <td class="w-100">{{ $section->articles()->count() }}</td>
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
            </div>
            <div class="col-md-6 col-lg-4">
                @include('partials.activity_logs')
            </div>
        </div>
    </div>
</div>
