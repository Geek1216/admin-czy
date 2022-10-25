@section('meta')
    <title>{{ __('Levels') }} &raquo; {{ $level->name }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('levels.index') }}">{{ __('Levels') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $level->name }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('levels.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Levels') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('levels.update', $level) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('levels.destroy', $level) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing level here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Name') }}</th>
                                <td class="w-100">{{ $level->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Color') }}</th>
                                <td class="w-100">
                                    <i class="fas fa-circle mr-1" style="color: #{{ $level->color }}"></i> #{{ $level->color }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Order') }}</th>
                                <td class="w-100">{{ $level->order }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Followers') }}</th>
                                <td class="w-100">
                                    @if ($level->followers === -1)
                                        <span class="text-muted">{{ __('Do not check') }}</span>
                                    @else
                                        &gt;&equals; {{ $level->followers }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Uploads') }}</th>
                                <td class="w-100">
                                    @if ($level->uploads === -1)
                                        <span class="text-muted">{{ __('Do not check') }}</span>
                                    @else
                                        &gt;&equals; {{ $level->uploads }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Views') }}</th>
                                <td class="w-100">
                                    @if ($level->views === -1)
                                        <span class="text-muted">{{ __('Do not check') }}</span>
                                    @else
                                        &gt;&equals; {{ $level->views }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Likes') }}</th>
                                <td class="w-100">
                                    @if ($level->likes === -1)
                                        <span class="text-muted">{{ __('Do not check') }}</span>
                                    @else
                                        &gt;&equals; {{ $level->likes }}
                                    @endif
                                </td>
                            </tr>
                            @if (config('fixtures.gifts_enabled'))
                                <tr>
                                    <th class="bg-light">{{ __('Reward') }}</th>
                                    <td class="w-100">{{ $level->reward }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $level->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $level->updated_at->format('d/m/Y H:i') }}
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
