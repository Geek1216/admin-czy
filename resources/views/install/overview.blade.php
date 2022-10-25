@extends('layouts.auth', ['main_columns' => 'col-md-10 col-lg-9 col-xl-8'])

@section('meta')
    <title>{{ __('Install') }} &raquo; {{ __('Overview') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-primary">{{ __('Overview') }}</h5>
            <p class="card-text">
                {{ __('Please make sure your server meets below minimum requirements before you continue:') }}
            </p>
        </div>
        <div class="table-responsive border-top">
            <table class="table table-borderless mb-0">
                <tbody>
                <tr>
                    <th class="w-25" scope="row">{{ __('PHP') }} <small>>= {{ $requirements['php'] }}</small></th>
                    <td class="w-25 @if ($status['php']) table-success text-success @else table-danger text-danger @endif" colspan="3">
                        @if ($status['php'])
                            <i class="fas fa-check-circle mr-1"></i>
                        @else
                            <i class="fas fa-times-circle mr-1"></i>
                        @endif
                        {{ PHP_VERSION }}
                    </td>
                </tr>
                <tr>
                    <td class="text-muted" colspan="4">{{ __('Extensions') }}</td>
                </tr>
                <tr>
                    @php
                        $columns = 0;
                    @endphp
                    @foreach ($requirements['extensions'] as $extension)
                        @if ($columns === 4)
                            @php
                                $columns = 0;
                            @endphp
                            </tr><tr>
                        @endif
                        <th class="w-25">{{ __($extension) }}</th>
                        <td class="w-25 @if ($status['extension:' . $extension]) table-success text-success @else table-danger text-danger @endif">
                            @if ($status['extension:' . $extension])
                                <i class="fas fa-check-circle mr-1"></i> {{ __('Installed') }}
                            @else
                                <i class="fas fa-times-circle mr-1"></i> {{ __('Not installed') }}
                            @endif
                        </td>
                        @php
                            $columns += 2;
                        @endphp
                    @endforeach
                </tr>
                <tr>
                    <td class="text-muted" colspan="4">{{ __('INI') }}</td>
                </tr>
                <tr>
                    <th class="w-25">max_execution_time <small>>= {{ $requirements['timeout'] }}</small></th>
                    <td class="w-25 @if ($status['ini:max_execution_time']) table-success text-success @else table-danger text-danger @endif">
                        @if ($status['ini:max_input_time'])
                            <i class="fas fa-check-circle mr-1"></i> {{ ini_get('max_input_time') }}
                        @else
                            <i class="fas fa-times-circle mr-1"></i> {{ ini_get('max_input_time') }}
                        @endif
                    </td>
                    <th class="w-25">upload_max_filesize <small>>= {{ $requirements['timeout'] }}</small></th>
                    <td class="w-25 @if ($status['ini:max_execution_time']) table-success text-success @else table-danger text-danger @endif">
                        @if ($status['ini:max_execution_time'])
                            <i class="fas fa-check-circle mr-1"></i> {{ ini_get('max_execution_time') }}
                        @else
                            <i class="fas fa-times-circle mr-1"></i> {{ ini_get('max_execution_time') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-25">memory_limit <small>>= {{ $requirements['memory'] }}</small></th>
                    <td class="w-25 @if ($status['ini:memory_limit']) table-success text-success @else table-danger text-danger @endif">
                        @if ($status['ini:memory_limit'])
                            <i class="fas fa-check-circle mr-1"></i> {{ ini_get('memory_limit') }}
                        @else
                            <i class="fas fa-times-circle mr-1"></i> {{ ini_get('memory_limit') }}
                        @endif
                    </td>
                    <th class="w-25">post_max_size <small>>= {{ $requirements['uploads'] }}</small></th>
                    <td class="w-25 @if ($status['ini:post_max_size']) table-success text-success @else table-danger text-danger @endif">
                        @if ($status['ini:post_max_size'])
                            <i class="fas fa-check-circle mr-1"></i> {{ ini_get('post_max_size') }}
                        @else
                            <i class="fas fa-times-circle mr-1"></i> {{ ini_get('post_max_size') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-25">upload_max_filesize <small>>= {{ $requirements['uploads'] }}</small></th>
                    <td class="w-25 @if ($status['ini:upload_max_filesize']) table-success text-success @else table-danger text-danger @endif">
                        @if ($status['ini:upload_max_filesize'])
                            <i class="fas fa-check-circle mr-1"></i> {{ ini_get('upload_max_filesize') }}
                        @else
                            <i class="fas fa-times-circle mr-1"></i> {{ ini_get('upload_max_filesize') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted" colspan="4">{{ __('Directories') }}</td>
                </tr>
                <tr>
                    @php
                        $columns = 0;
                    @endphp
                    @foreach ($requirements['directories'] as $directory)
                        @if ($columns === 4)
                            @php
                                $columns = 0;
                            @endphp
                            </tr><tr>
                        @endif
                        <th class="w-25">
                            ~{{ __($directory) }}
                        </th>
                        <td class="w-25 @if ($status['directory:' . $directory]) table-success text-success @else table-danger text-danger @endif">
                            @if ($status['directory:' . $directory])
                                <i class="fas fa-check-circle mr-1"></i> {{ __('Writable') }}
                            @else
                                <i class="fas fa-times-circle mr-1"></i> {{ __('Not writable') }}
                            @endif
                        </td>
                        @php
                            $columns += 2;
                        @endphp
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            <div class="btn-toolbar">
                <a class="btn btn-primary ml-auto" href="{{ route('install.configure') }}">
                    {{ __('Continue') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
