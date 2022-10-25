@section('meta')
    <title>{{ __('Notification schedules') }} &raquo; {{ $schedule->time }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notification-schedules.index') }}">{{ __('Notification schedules') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $schedule->time }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('notification-schedules.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Notification schedules') }}
            </a>
            <button class="btn btn-warning ml-auto" wire:click="send" wire:loading.attr="disabled">
                <i class="fas fa-bullhorn mr-1"></i> {{ __('Send') }}
            </button>
            <a class="btn btn-info ml-1" href="{{ route('notification-schedules.update', $schedule) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('notification-schedules.destroy', $schedule) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing notification schedule here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Template') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($schedule->template)
                                        <a href="{{ route('notification-templates.show', $schedule->template) }}">
                                            {{ $schedule->template->title_short }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('None') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Time') }}</th>
                                <td class="w-100">{{ $schedule->time }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Clip') }}</th>
                                <td class="w-100">
                                    @if ($schedule->clip)
                                        {{ config('fixtures.notification_schedule_clips.' . $schedule->clip) }}
                                    @else
                                        <span class="text-muted">{{ __('None') }}</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $schedule->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $schedule->updated_at->format('d/m/Y H:i') }}
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
