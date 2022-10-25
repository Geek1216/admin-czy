@extends('layouts.app', [
    'html_class' => 'w-100 h-100',
    'body_class' => 'w-100 h-100 d-flex',
])

@section('meta')
    <title>{{ __('Installation') }} | {{ config('app.name') }}</title>
@endsection

@section('body')
    <div class="container my-auto py-3">
        <div class="row justify-content-center">
            <main class="col-md-10 col-lg-8">
                @include('flash::message')
                @php
                    $active_tab = old('tab', 'status');
                @endphp
                <div class="card shadow-sm">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link @if ($active_tab === 'status') active @endif" data-toggle="tab" href="#installation-status" id="installation-status-tab" role="tab" aria-controls="installation-status" @if ($active_tab === 'status') aria-selected="true" @else aria-selected="false" @endif>
                                    {{ __('Status') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link @if ($active_tab === 'configure') active @endif" data-toggle="tab" href="#installation-configure" id="installation-configure-tab" role="tab" aria-controls="installation-configure" @if ($active_tab === 'configure') aria-selected="true" @else aria-selected="false" @endif>
                                    {{ __('Configure') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane @if ($active_tab === 'status') show active @endif" id="installation-status" role="tabpanel" aria-labelledby="installation-status-tab">
                            <div class="card-body">
                                <p class="card-text">
                                    {{ __('Review server settings and make sure all requirements are met before you install.') }}
                                </p>
                            </div>
                            <div class="table-responsive border-top">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                    <tr>
                                        <th class="w-25" scope="row">{{ __('PHP') }} <small>>= {{ $requirements['php'] }}</small></th>
                                        <td class="w-25 @if ($status['php']) table-success @else table-danger @endif" colspan="3">
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
                                            <td class="w-25 @if ($status['ext:' . $extension]) table-success @else table-danger @endif">
                                                @if ($status['ext:' . $extension])
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
                                        <td class="text-muted" colspan="4">{{ __('INI installation') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">max_execution_time <small>>= {{ $requirements['timeout'] }}</small></th>
                                        <td class="w-25 @if ($status['ini:max_execution_time']) table-success @else table-danger @endif">
                                            @if ($status['ini:max_execution_time'])
                                                <i class="fas fa-check-circle mr-1"></i> {{ ini_get('max_execution_time') }}
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> {{ ini_get('max_execution_time') }}
                                            @endif
                                        </td>
                                        <th class="w-25">max_input_time <small>>= {{ $requirements['timeout'] }}</small></th>
                                        <td class="w-25 @if ($status['ini:max_input_time']) table-success @else table-danger @endif">
                                            @if ($status['ini:max_input_time'])
                                                <i class="fas fa-check-circle mr-1"></i> {{ ini_get('max_input_time') }}
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> {{ ini_get('max_input_time') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">memory_limit <small>>= {{ $requirements['memory'] }}</small></th>
                                        <td class="w-25 @if ($status['ini:memory_limit']) table-success @else table-danger @endif">
                                            @if ($status['ini:memory_limit'])
                                                <i class="fas fa-check-circle mr-1"></i> {{ ini_get('memory_limit') }}
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> {{ ini_get('memory_limit') }}
                                            @endif
                                        </td>
                                        <th class="w-25">post_max_size <small>>= {{ $requirements['uploads'] }}</small></th>
                                        <td class="w-25 @if ($status['ini:post_max_size']) table-success @else table-danger @endif">
                                            @if ($status['ini:post_max_size'])
                                                <i class="fas fa-check-circle mr-1"></i> {{ ini_get('post_max_size') }}
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> {{ ini_get('post_max_size') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">upload_max_filesize <small>>= {{ $requirements['uploads'] }}</small></th>
                                        <td class="w-25 @if ($status['ini:upload_max_filesize']) table-success @else table-danger @endif">
                                            @if ($status['ini:upload_max_filesize'])
                                                <i class="fas fa-check-circle mr-1"></i> {{ ini_get('upload_max_filesize') }}
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> {{ ini_get('upload_max_filesize') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted" colspan="4">{{ __('Functions') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">symlink(&hellip;)</th>
                                        <td class="w-25 @if ($status['func:symlink']) table-success @else table-warning @endif">
                                            @if ($status['func:symlink'])
                                                <i class="fas fa-check-circle mr-1"></i> {{ __('Enabled') }}
                                            @else
                                                <i class="fas fa-exclamation-circle mr-1"></i> {{ __('Disabled') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted" colspan="4">{{ __('Permissions') }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $columns = 0;
                                        @endphp
                                        @foreach ($requirements['writable'] as $path)
                                            @if ($columns === 4)
                                                @php
                                                    $columns = 0;
                                                @endphp
                                                </tr><tr>
                                            @endif
                                            <th class="w-25">
                                                ~/{{ __($path) }}
                                            </th>
                                            <td class="w-25 @if ($status['writable:' . $path]) table-success @else table-danger @endif">
                                                @if ($status['writable:' . $path])
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
                        </div>
                        <div class="tab-pane @if ($active_tab === 'configure') show active @endif" id="installation-configure" role="tabpanel" aria-labelledby="installation-configure-tab">
                            <div class="card-body">
                                <p class="card-text">{{ __('Fill the database details below and submit to install.') }}</p>
                            </div>
                            <div class="card-body border-top">
                                <form action="{{ route('installation.submit') }}" method="post">
                                    @csrf
                                    <input name="tab" type="hidden" value="configure">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="installation-app-timezone">
                                            {{ __('Timezone') }} <span class="text-danger">&ast;</span>
                                        </label>
                                        <div class="col-sm-8">
                                            @php
                                                $old_app_timezone = old('app_timezone', 'UTC');
                                            @endphp
                                            <select class="form-control @error('app_timezone') is-invalid @enderror" data-widget="select2" id="installation-app-timezone" name="app_timezone" required>
                                                @foreach (timezone_identifiers_list() as $timezone)
                                                    <option value="{{ $timezone }}" @if ($old_app_timezone === $timezone) selected @endif>{{ $timezone }}</option>
                                                @endforeach
                                            </select>
                                            @error('app_timezone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="installation-db-connection">
                                            {{ __('Driver') }} <span class="text-danger">&ast;</span>
                                        </label>
                                        <div class="col-sm-8">
                                            @php
                                                $old_db_connection = old('db_connection', 'mysql');
                                            @endphp
                                            <select class="form-control @error('db_connection') is-invalid @enderror" data-widget="select2" id="installation-db-connection" name="db_connection" required>
                                                <option value="mysql" @if ($old_db_connection === 'mysql') selected @endif>{{ __('MySQL') }}</option>
                                                <option value="pgsql" @if ($old_db_connection === 'pgsql') selected @endif>{{ __('Postgres') }}</option>
                                            </select>
                                            @error('db_connection')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="installation-db-host">
                                            {{ __('Host & port') }} <span class="text-danger">&ast;</span>
                                        </label>
                                        <div class="col-sm-8 col-md-4">
                                            <div class="mb-3 mb-md-0">
                                                <input class="form-control @error('db_host') is-invalid @enderror" id="installation-db-host" name="db_host" required value="{{ old('db_host') }}">
                                                @error('db_host')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-md-4 offset-sm-4 offset-md-0">
                                            <!--suppress HtmlFormInputWithoutLabel -->
                                            <input class="form-control @error('db_port') is-invalid @enderror" id="installation-db-port" name="db_port" required type="number" value="{{ old('db_port') }}">
                                            @error('db_port')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="installation-db-database">
                                            {{ __('Database') }} <span class="text-danger">&ast;</span>
                                        </label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('db_database') is-invalid @enderror" id="installation-db-database" name="db_database" required value="{{ old('db_database') }}">
                                            @error('db_database')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="installation-db-username">
                                            {{ __('Username & password') }} <span class="text-danger">&ast;</span>
                                        </label>
                                        <div class="col-sm-8 col-md-4">
                                            <div class="mb-3 mb-md-0">
                                                <input class="form-control @error('db_username') is-invalid @enderror" id="installation-db-username" name="db_username" required value="{{ old('db_username') }}">
                                                @error('db_username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-md-4 offset-sm-4 offset-md-0">
                                            <!--suppress HtmlFormInputWithoutLabel -->
                                            <input class="form-control @error('db_password') is-invalid @enderror" id="installation-db-password" name="db_password" required type="password">
                                            @error('db_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 offset-sm-4">
                                            <button class="btn btn-primary">
                                                <i class="fas fa-check-circle mr-1"></i> {{ __('Install') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-right mb-0">
                    <small>
                        <strong>{{ config('app.name') }}</strong> <abbr data-toggle="tooltip" title="{{ config('fixtures.git_commit') }}">{{ substr(config('fixtures.git_commit'), 0, 8) }}</abbr> &copy; {{ date('Y') }}
                    </small>
                </p>
            </main>
        </div>
    </div>
@endsection
