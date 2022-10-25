@section('meta')
    <title>{{ __('Comments') }} &raquo; {{ $comment->comment_short }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('comments.index') }}">{{ __('Comments') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $comment->comment_short }}</li>
            </ol>
        </nav>
        <div class="btn-toolbar mb-3">
            <a class="btn btn-outline-dark" href="{{ route('comments.index') }}">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Comments') }}
            </a>
            @can('administer')
                <a class="btn btn-danger ml-auto" href="{{ route('comments.destroy', $comment) }}">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card shadow-sm mb-3 mb-md-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('Details') }}</h5>
                        <p class="card-text">{{ __('See information about existing comment here.') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <th class="bg-light">{{ __('User') }}</th>
                                <td class="w-100">
                                    <a href="{{ route('users.show', $comment->commentator) }}">{{ $comment->commentator->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Clip') }}</th>
                                <td class="w-100">
                                    @if ($comment->commentable->description)
                                        <a href="{{ route('clips.show', $comment->commentable) }}">{{ $comment->commentable->description_short }}</a>
                                    @else
                                        <a href="{{ route('clips.show', $comment->commentable) }}">Clip #{{ $comment->commentable->id }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light align-text-top">{{ __('Comment') }}</th>
                                <td class="w-100 text-wrap">{{ $comment->comment }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{ __('Reports') }}</th>
                                <td class="w-100">{{ $comment->reports()->count() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">{{ __('Created at') }}</span> {{ $comment->created_at->format('d/m/Y H:i') }}
                        <span class="d-none d-md-inline">
                            &bull;
                            <span class="text-muted">{{ __('Updated at') }}</span> {{ $comment->updated_at->format('d/m/Y H:i') }}
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
