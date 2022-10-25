@section('meta')
    <title>{{ __('Promotions') }} &raquo; {{ $promotion->title }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}">{{ __('Promotions') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $promotion->title }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('promotions.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Promotions') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('promotions.update', $promotion) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('promotions.destroy', $promotion) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing promotion here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Title') }}</th>
                                <td class="w-100">{{ $promotion->title }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Description') }}</th>
                                <td class="w-100 text-wrap">
                                    @if ($promotion->description)
                                        {{ $promotion->description }}
                                    @else
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Image') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($promotion->image) }}" rel="noopener noreferrer" target="_blank">{{ $promotion->image }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Sticky?') }}</th>
                                <td class="w-100">
                                    @if ($promotion->sticky)
                                        <i class="fas fa-toggle-on mr-1 text-success"></i>
                                    @else
                                        <i class="fas fa-toggle-off mr-1 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $promotion->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $promotion->updated_at->format('d/m/Y H:i') }}
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
