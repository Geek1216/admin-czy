@section('meta')
    <title>{{ __('Notification templates') }} &raquo; {{ $template->title_short }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notification-templates.index') }}">{{ __('Notification templates') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $template->title_short }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('notification-templates.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Notification templates') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('notification-templates.update', $template) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('notification-templates.destroy', $template) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing notification template here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Title') }}</th>
                                <td class="w-100 text-wrap">{{ $template->title }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Body') }}</th>
                                <td class="w-100 text-wrap">{{ $template->body }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Schedules') }}</th>
                                <td class="w-100">{{ $template->schedules()->count() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $template->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $template->updated_at->format('d/m/Y H:i') }}
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
