@section('meta')
    <title>{{ __('Reports') }} &raquo; #{{ $report->id }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">{{ __('Reports') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $report->id }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('reports.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Reports') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('reports.update', $report) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('reports.destroy', $report) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing report here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $report->user) }}">{{ $report->user->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Subject') }}</th>
                                <td class="w-100">
                                    @if ($report->subject instanceof App\Clip)
                                        <a href="{{ route('clips.show', $report->subject) }}">
                                            @if ($report->subject->description)
                                                {{ $report->subject->description_short }}
                                            @else
                                                {{ __('Clip #:id', ['id' => $report->subject->id]) }}
                                            @endif
                                        </a>
                                    @elseif ($report->subject instanceof App\Comment)
                                        <a href="{{ route('comments.show', $report->subject) }}">{{ $report->subject->comment_short }}</a>
                                    @elseif ($report->subject instanceof App\User)
                                        <a href="{{ route('users.show', $report->subject) }}">{{ $report->user->name }}</a>
                                    @else
                                        <span class="text-muted">{{ __('Deleted') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Reason') }}</th>
                                <td class="w-100">{{ config('fixtures.report_reasons.' . $report->reason) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Message') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($report->message)
                                        {{ $report->message }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Status') }}</th>
                                <td class="w-100">{{ config('fixtures.report_statuses.' . $report->status) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $report->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $report->updated_at->format('d/m/Y H:i') }}
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
