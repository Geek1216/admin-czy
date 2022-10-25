@section('meta')
    <title>{{ __('Verifications') }} &raquo; #{{ $verification->id }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('verifications.index') }}">{{ __('Verifications') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $verification->id }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('verifications.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Verifications') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('verifications.update', $verification) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('verifications.destroy', $verification) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing verification here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $verification->user) }}">{{ $verification->user->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Username') }}</th>
                                <td class="w-100">&commat;{{ $verification->user->username }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Document') }}</th>
                                <td class="w-100">
                                    @if (config('filesystems.cloud') === 'public')
                                        <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($verification->document) }}" target="_blank">
                                            {{ $verification->document }}
                                        </a>
                                    @else
                                        <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->temporaryUrl($verification->document, now()->addHour()) }}" target="_blank">
                                            {{ $verification->document }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Business?') }}</th>
                                <td class="w-100">
                                    @if ($verification->business)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Status') }}</th>
                                <td class="w-100">
                                    @if ($verification->status === 'accepted')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle mr-1"></i> {{ __('Accepted') }}
                                        </span>
                                    @elseif ($verification->status === 'rejected')
                                        <span class="text-danger">
                                            <i class="fas fa-times-circle mr-1"></i> {{ __('Rejected') }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-clock mr-1"></i> {{ __('Pending') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $verification->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $verification->updated_at->format('d/m/Y H:i') }}
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
