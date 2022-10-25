@section('meta')
    <title>{{ __('Items') }} &raquo; {{ $item->name }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.index') }}">{{ __('Items') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('items.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Items') }}
            </a>
            <a class="btn btn-info ml-auto" href="{{ route('items.update', $item) }}">
                <i class="fas fa-feather mr-1"></i> {{ __('Edit') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-1" href="{{ route('items.destroy', $item) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing item here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('Name') }}</th>
                                <td class="w-100">{{ $item->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Image') }}</th>
                                <td class="w-100">
                                    <a href="{{ Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($item->image) }}" rel="noopener noreferrer" target="_blank">{{ $item->image }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Price') }}</th>
                                <td class="w-100">{{ $item->price }} <span class="text-muted">{{ __('Credits') }}</span></td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Value') }}</th>
                                <td class="w-100">
                                    @if (setting('payment_currency', config('fixtures.payment_currency')) === 'BTC')
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ (float) $item->value }}
                                    @else
                                        <span class="text-muted">{{ setting('payment_currency', config('fixtures.payment_currency')) }}</span> {{ $item->value / 100 }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Minimum') }}</th>
                                <td class="w-100">{{ $item->minimum }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $item->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $item->updated_at->format('d/m/Y H:i') }}
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
