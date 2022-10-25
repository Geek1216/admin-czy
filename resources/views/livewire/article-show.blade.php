@section('meta')
    <title>{{ __('Articles') }} &raquo; {{ $article->title_short }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">{{ __('Articles') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $article->title_short }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('articles.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Articles') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('articles.update', $article) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('articles.destroy', $article) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing article here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Sections') }}</th>
                                <td class="w-100">
                                    @forelse ($article->sections as $section)
                                        <span class="badge badge-light">{{ $section->name }}</span>
                                    @empty
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Title') }}</th>
                                <td class="w-100 text-wrap">{{ $article->title }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Snippet') }}</th>
                                <td class="w-100 text-wrap">{{ $article->snippet }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Image') }}</th>
                                <td class="w-100">
                                    @if ($article->image)
                                        <a href="{{ $article->image }}" rel="noopener noreferrer" target="_blank">{{ $article->image_short }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Link') }}</th>
                                <td class="w-100">
                                    <a href="{{ $article->link }}" rel="noopener noreferrer" target="_blank">{{ $article->link_short }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Source') }}</th>
                                <td class="w-100">
                                    @if ($article->source)
                                        {{ $article->source }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Published at') }}</th>
                                <td class="w-100">{{ $article->published_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $article->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $article->updated_at->format('d/m/Y H:i') }}
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
