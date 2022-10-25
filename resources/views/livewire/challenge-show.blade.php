@section('meta')
    <title>{{ __('Challenges') }} &raquo; {{ $challenge->hashtag }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}">{{ __('Challenges') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $challenge->hashtag }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('challenges.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Challenges') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('challenges.update', $challenge) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('challenges.destroy', $challenge) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing challenge here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Hashtag') }}</th>
                                <td class="w-100">#{{ $challenge->hashtag }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Image') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($challenge->image) }}" rel="noopener noreferrer" target="_blank">{{ $challenge->image }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Description') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($challenge->description)
                                        {{ $challenge->description }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Submissions') }}</th>
                                <td class="w-100">{{ $challenge->clips()->count() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $challenge->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $challenge->updated_at->format('d/m/Y H:i') }}
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
