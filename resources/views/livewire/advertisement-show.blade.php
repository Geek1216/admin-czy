@section('meta')
    <title>{{ __('Advertisements') }} &raquo; #{{ $advertisement->id }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('advertisements.index') }}">{{ __('Advertisements') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $advertisement->id }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('advertisements.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Advertisements') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('advertisements.update', $advertisement) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('advertisements.destroy', $advertisement) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing advertisement here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Location') }}</th>
                                <td class="w-100">{{ config('fixtures.advertisement_locations.' . $advertisement->location) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Network') }}</th>
                                <td class="w-100">{{ config('fixtures.advertisement_networks.' . $advertisement->network) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Type') }}</th>
                                <td class="w-100">{{ config('fixtures.advertisement_types.' . $advertisement->type) }}</td>
                            </tr>
                            @if ($advertisement->network !== 'custom')
                                <tr>
                                    <th class="bg-light">{{ __('Placement / unit') }}</th>
                                    <td class="w-100">{{ $advertisement->unit }}</td>
                                </tr>
                            @else
                                <tr>
                                    <th class="bg-light">{{ __('Image') }}</th>
                                    <td class="w-100">
                                        @if ($advertisement->image)
                                            <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($advertisement->image) }}" rel="noopener noreferrer" target="_blank">{{ $advertisement->image }}</a>
                                        @else
                                            <span class="text-muted">{{ __('None') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('Link') }}</th>
                                    <td class="w-100">
                                        @if ($advertisement->link)
                                            <a href="{{ $advertisement->link }}" rel="noopener noreferrer" target="_blank">{{ $advertisement->link }}</a>
                                        @else
                                            <span class="text-muted">{{ __('None') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th class="bg-light">{{ __('Interval') }}</th>
                                <td class="w-100">
                                    @if ($advertisement->type !== 'banner')
                                        {{ $advertisement->interval }} {{ __('Items') }}
                                    @else
                                        <span class="text-muted">{{ __('n/a') }}</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $advertisement->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $advertisement->updated_at->format('d/m/Y H:i') }}
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
