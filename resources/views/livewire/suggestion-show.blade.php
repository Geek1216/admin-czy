@section('meta')
    <title>{{ __('Suggestions') }} &raquo; {{ $suggestion->user->name }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('suggestions.index') }}">{{ __('Suggestions') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $suggestion->user->name }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('suggestions.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Suggestions') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('suggestions.update', $suggestion) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('suggestions.destroy', $suggestion) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing suggestion here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $suggestion->user) }}">{{ $suggestion->user->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Followers') }}</th>
                                <td class="w-100">{{ $suggestion->user->followers()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Followers') }}</th>
                                <td class="w-100">{{ $suggestion->user->followers()->count() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Sort order') }}</th>
                                <td class="w-100">{{ $suggestion->order }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $suggestion->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $suggestion->updated_at->format('d/m/Y H:i') }}
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
