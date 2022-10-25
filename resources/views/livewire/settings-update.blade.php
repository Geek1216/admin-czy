@section('meta')
    <title>{{ __('Settings') }} - {{ __('Backend') }} | {{ config('app.name') }}</title>
@endsection

<div>
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->keys() as $field)
                        <li><i class="fas fa-times-circle mr-1"></i> {{ $errors->first($field) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Settings') }}</li>
            </ol>
        </nav>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="spinner-border spinner-border-sm float-right" role="status" wire:loading wire:target="update">
                    <span class="sr-only">{{ __('Loading') }}&hellip;</span>
                </div>
                <h5 class="card-title text-primary">{{ __('Settings') }}</h5>
                <p class="card-text">
                    {{ __('Manage application settings here. Please bear in mind, the values set here supersede those set in .env file.') }}
                </p>
            </div>
            <div class="card-body border-top">
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'api') active @endif" href="" id="settings-api-tab" wire:click.prevent="change('api')">
                            {{ __('API') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'cache') active @endif" href="" id="settings-cache-tab" wire:click.prevent="change('cache')">
                            {{ __('Cache') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'email') active @endif" href="" id="settings-email-tab" wire:click.prevent="change('email')">
                            {{ __('Email') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'firebase') active @endif" href="" id="settings-firebase-tab" wire:click.prevent="change('firebase')">
                            {{ __('Firebase') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'live') active @endif" href="" id="settings-live-tab" wire:click.prevent="change('live')">
                            {{ __('Live-stream') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'gifts') active @endif" href="" id="settings-gifts-tab" wire:click.prevent="change('gifts')">
                            {{ __('Gifts') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'nsfw') active @endif" href="" id="settings-nsfw-tab" wire:click.prevent="change('nsfw')">
                            {{ __('NSFW') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'queue') active @endif" href="" id="settings-queue-tab" wire:click.prevent="change('queue')">
                            {{ __('Queue') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'referral') active @endif" href="" id="settings-referral-tab" wire:click.prevent="change('referral')">
                            {{ __('Referral') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'sms') active @endif" href="" id="settings-sms-tab" wire:click.prevent="change('sms')">
                            {{ __('SMS') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'social') active @endif" href="" id="settings-social-tab" wire:click.prevent="change('social')">
                            {{ __('Social') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tab === 'storage') active @endif" href="" id="settings-storage-tab" wire:click.prevent="change('storage')">
                            {{ __('Storage') }}
                        </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <form class="mb-0" wire:submit.prevent="update">
                            @if ($tab === 'api')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-api-key">{{ __('Key') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input class="form-control text-monospace @error('api_key') is-invalid @enderror" id="setting-api-key" readonly value="{{ $api_key }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-dark" type="button" wire:click="regenerate" wire:loading.attr="disabled" wire:target="regenerate">
                                                    <i class="fas fa-sync mr-1" wire:loading.class="fa-spin" wire:target="regenerate"></i> {{ __('Regenerate') }}
                                                </button>
                                            </div>
                                        </div>
                                        @error('api_key')
                                            <div class="is-invalid" style="display: none"></div>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('This is a random, generated key to secure API from direct access.') }}
                                            {{ __('You should copy this value as you may need this to correctly configure the app.') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-api-key-enabled">{{ __('Key enabled?') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-switch mt-sm-2">
                                            <input class="custom-control-input @error('api_key_enabled') is-invalid @enderror" id="setting-api-key-enabled" type="checkbox" wire:model="api_key_enabled" value="1">
                                            <label class="custom-control-label" for="setting-api-key-enabled">{{ __('Yes') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-ranking-algorithm">{{ __('Ranking algorithm') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('ranking_algorithm') is-invalid @enderror" id="setting-ranking-algorithm" required wire:model="ranking_algorithm">
                                            <option value="sequential">{{ __('Sequential') }}</option>
                                            <option value="random">{{ __('Random') }}</option>
                                        </select>
                                        @error('ranking_algorithm')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @elseif ($tab === 'cache')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-cache-default">{{ __('Driver') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('cache_default') is-invalid @enderror" id="setting-cache-default" required wire:model="cache_default">
                                            <option value="file">{{ __('File') }}</option>
                                            <option value="redis">{{ __('Redis') }}</option>
                                        </select>
                                        @error('cache_default')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('Setting this to "redis" can significantly increase performance as this script does a lot of caching.') }}
                                            {{ __('It requires Redis to be installed and more RAM to be available on server.') }}
                                        </small>
                                    </div>
                                </div>
                            @elseif ($tab === 'email')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-mail-driver">{{ __('Driver') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('mail_driver') is-invalid @enderror" data-widget="select2" id="setting-mail-driver" wire:model="mail_driver" required>
                                            <option value="sendmail">{{ __('Sendmail') }}</option>
                                            <option value="smtp">{{ __('SMTP') }}</option>
                                            <option value="mailgun">{{ __('Mailgun') }}</option>
                                        </select>
                                        @error('mail_driver')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-mail-from-name">{{ __('From name') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('mail_from_name') is-invalid @enderror" id="setting-mail-from-name" required wire:model="mail_from_name">
                                        @error('mail_from_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('This will be shown to your recipients in "From" header.') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-mail-from-address">{{ __('From address') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('mail_from_address') is-invalid @enderror" id="setting-mail-from-address" required type="email" wire:model="mail_from_address">
                                        @error('mail_from_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('This will also be shown to your recipients in "From" header.') }}
                                        </small>
                                    </div>
                                </div>
                                @if ($mail_driver === 'smtp')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mail-host">{{ __('Hostname / IP address') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('mail_host') is-invalid @enderror" id="setting-mail-host" required wire:model="mail_host">
                                            @error('mail_host')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mail-port">{{ __('Port') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('mail_port') is-invalid @enderror" id="setting-mail-port" required type="number" wire:model="mail_port">
                                            @error('mail_port')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mail-username">{{ __('Username') }}</label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('mail_username') is-invalid @enderror" id="setting-mail-username" wire:model="mail_username">
                                            @error('mail_username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('This is usually the same as your "From" address.') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mail-password">{{ __('Password') }}</label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('mail_password') is-invalid @enderror" id="setting-mail-password" type="password" wire:model="mail_password">
                                            @error('mail_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mail-encryption">{{ __('Encryption') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control @error('mail_encryption') is-invalid @enderror" id="setting-mail-encryption" required wire:model="mail_encryption">
                                                <option value="ssl">{{ __('SSL') }}</option>
                                                <option value="tls">{{ __('TLS') }}</option>
                                            </select>
                                            @error('mail_encryption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @elseif ($mail_driver === 'mailgun')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mailgun-domain">{{ __('Domain') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('mailgun_domain') is-invalid @enderror" id="setting-mailgun-domain" required wire:model="mailgun_domain">
                                            @error('mailgun_domain')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('This is the domain you may have already verified with Mailgun e.g., mg.yourapp.com etc.') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-mailgun-secret">{{ __('Secret') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('mailgun_secret') is-invalid @enderror" id="setting-mailgun-secret" required wire:model="mailgun_secret">
                                            @error('mailgun_secret')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @elseif ($tab === 'firebase')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-firebase-credentials">{{ __('Key file') }}</label>
                                    <div class="col-sm-8">
                                        <div class="custom-file">
                                            <input class="custom-file-input @error('firebase_credentials') is-invalid @enderror" id="setting-firebase-credentials" type="file" wire:model="firebase_credentials">
                                            <label class="custom-file-label" for="setting-firebase-credentials">
                                                @if ($firebase_credentials)
                                                    {{ 'temporary.'.$firebase_credentials->extension() }}
                                                @else
                                                    {{ __('Choose file') }}&hellip;
                                                @endif
                                            </label>
                                            @error('firebase_credentials')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('This must be the service account key file associated with your Firebase project.') }}
                                                {{ __('You can generate/download it from Firebase console.') }}
                                            </small>
                                        </div>
                                        <small class="text-muted form-text" wire:loading wire:target="firebase_credentials">
                                            {{ __('Uploading') }}&hellip;
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-firebase-dynamic-links-domain">{{ __('Dynamic links domain') }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('firebase_dynamic_links_domain') is-invalid @enderror" id="setting-firebase-dynamic-links-domain" wire:model="firebase_dynamic_links_domain">
                                        @error('firebase_dynamic_links_domain')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('Do not prefix with http:// or https://, just the domain added in Firebase console.') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-firebase-package-name">{{ __('Package name') }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('firebase_package_name') is-invalid @enderror" id="setting-firebase-package-name" wire:model="firebase_package_name">
                                        @error('firebase_package_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @elseif ($tab === 'live')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-live-streaming-enabled">{{ __('Enabled?') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-switch mt-sm-2">
                                            <input class="custom-control-input @error('live_streaming_enabled') is-invalid @enderror" id="setting-live-streaming-enabled" type="checkbox" wire:model="live_streaming_enabled" value="1">
                                            <label class="custom-control-label" for="setting-live-streaming-enabled">{{ __('Yes') }}</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($live_streaming_enabled)
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-live-streaming-service">{{ __('Service') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control @error('live_streaming_service') is-invalid @enderror" id="setting-live-streaming-service" required wire:model="live_streaming_service">
                                                <option value="">{{ __('Select') }}&hellip;</option>
                                                @foreach (config('fixtures.live_streaming_services') as $code => $name)
                                                    <option value="{{ $code }}">{{ $name  }}</option>
                                                @endforeach
                                            </select>
                                            @error('live_streaming_service')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if ($live_streaming_service === 'agora')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="live-agora-app-id">{{ __('App ID') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('agora_app_id') is-invalid @enderror" id="live-agora-app-id" required wire:model="agora_app_id">
                                                @error('agora_app_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="live-agora-app-certificate">{{ __('App certificate') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('agora_app_certificate') is-invalid @enderror" id="live-agora-app-certificate" required wire:model="agora_app_certificate">
                                                @error('agora_app_certificate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @elseif ($tab === 'gifts')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-gifts-enabled">{{ __('Enabled?') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-switch mt-sm-2">
                                            <input class="custom-control-input @error('gifts_enabled') is-invalid @enderror" id="setting-gifts-enabled" type="checkbox" wire:model="gifts_enabled" value="1">
                                            <label class="custom-control-label" for="setting-gifts-enabled">{{ __('Yes') }}</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($gifts_enabled)
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-payment-currency">{{ __('Currency') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('payment_currency') is-invalid @enderror" id="setting-payment-currency" required wire:model="payment_currency">
                                            @error('payment_currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('This is currency shown and used with payment methods.') }}
                                                {{ __('Enter 3-digit ISO code e.g., INR for Indian Rupee or USD for US Dollar.') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-payment-gateway">{{ __('Gateway') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control @error('payment_gateway') is-invalid @enderror" id="setting-payment-gateway" required wire:model="payment_gateway">
                                                <option value="">{{ __('Select') }}&hellip;</option>
                                                @foreach (config('fixtures.payment_gateways') as $code => $name)
                                                    <option value="{{ $code }}">{{ $name  }}</option>
                                                @endforeach
                                            </select>
                                            @error('payment_gateway')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if ($payment_gateway === 'play_store')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="setting-play-console-credentials">{{ __('Key file') }}</label>
                                            <div class="col-sm-8">
                                                <div class="custom-file">
                                                    <input class="custom-file-input @error('play_console_credentials') is-invalid @enderror" id="setting-play-console-credentials" type="file" wire:model="play_console_credentials">
                                                    <label class="custom-file-label" for="setting-play_console-credentials">
                                                        @if ($play_console_credentials)
                                                            {{ 'temporary.'.$play_console_credentials->extension() }}
                                                        @else
                                                            {{ __('Choose file') }}&hellip;
                                                        @endif
                                                    </label>
                                                    @error('play_console_credentials')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        {{ __('This must be the service account key file associated with your Google Play Developer Account.') }}
                                                        {{ __('You can generate this from Play Store > Settings > Developer account > API access > Choose a project to link > Create new service account.') }}
                                                    </small>
                                                </div>
                                                <small class="text-muted form-text" wire:loading wire:target="play_console_credentials">
                                                    {{ __('Uploading') }}&hellip;
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-play-store-package-name">{{ __('Package name') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('play_store_package_name') is-invalid @enderror" id="configure-play-store-package-name" required wire:model="play_store_package_name">
                                                @error('play_store_package_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    {{ __('This is package name of your app in Play Store.') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($payment_gateway === 'bitpay')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-bitpay-token">{{ __('Token') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('bitpay_token') is-invalid @enderror" id="configure-bitpay-token" required wire:model="bitpay_token">
                                                @error('bitpay_token')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    {{ __('You can generate this in BitPay > Payment Tools > API Token > Add New Token in Tokens sections.') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($payment_gateway === 'instamojo')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-instamojo-client-id">{{ __('Client ID') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('instamojo_client_id') is-invalid @enderror" id="configure-instamojo-client-id" required wire:model="instamojo_client_id">
                                                @error('instamojo_client_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-instamojo-client-secret">{{ __('Client secret') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('instamojo_client_secret') is-invalid @enderror" id="configure-instamojo-client-secret" required wire:model="instamojo_client_secret">
                                                @error('instamojo_client_secret')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if ($payment_gateway === 'paypal')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-paypal-client-id">{{ __('Client ID') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('paypal_client_id') is-invalid @enderror" id="configure-paypal-client-id" required wire:model="paypal_client_id">
                                                @error('paypal_client_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-paypal-client-secret">{{ __('Client secret') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('paypal_client_secret') is-invalid @enderror" id="configure-paypal-client-secret" required wire:model="paypal_client_secret">
                                                @error('paypal_client_secret')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if ($payment_gateway === 'razorpay')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-razorpay-key-id">{{ __('Key ID') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('razorpay_key_id') is-invalid @enderror" id="configure-razorpay-key-id" required wire:model="razorpay_key_id">
                                                @error('razorpay_key_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-razorpay-key-secret">{{ __('Key secret') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('razorpay_key_secret') is-invalid @enderror" id="configure-razorpay-key-secret" required wire:model="razorpay_key_secret">
                                                @error('razorpay_key_secret')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if ($payment_gateway === 'stripe')
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-stripe-publishable-key">{{ __('Publishable key') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('stripe_publishable_key') is-invalid @enderror" id="configure-stripe-publishable-key" required wire:model="stripe_publishable_key">
                                                @error('stripe_publishable_key')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="configure-stripe-secret-key">{{ __('Secret key') }} <span class="text-danger">&ast;</span></label>
                                            <div class="col-sm-8">
                                                <input class="form-control @error('stripe_secret_key') is-invalid @enderror" id="configure-stripe-secret-key" required wire:model="stripe_secret_key">
                                                @error('stripe_secret_key')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @elseif ($tab === 'nsfw')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-screening-service">{{ __('Service') }}</label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('screening_service') is-invalid @enderror" id="setting-screening-service" wire:model="screening_service">
                                            <option value="">{{ __('Disabled') }}</option>
                                            <option value="aws">{{ __('AWS Rekognition') }}</option>
                                            <option value="gcp">{{ __('Google Cloud Video Intelligence') }}</option>
                                            <option value="sightengine">{{ __('Sightengine') }}</option>
                                        </select>
                                        @error('screening_service')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('To use AWS Rekognition, storage driver must be set to S3.') }}
                                            {{ __('Similarly, storage driver must be set to Google Cloud Storage in order to use Google Cloud Video Intelligence.') }}
                                            {{ __('Sightengine works with any storage driver.') }}
                                        </small>
                                    </div>
                                </div>
                                @if ($screening_service === 'sightengine')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-sightengine-api-user">{{ __('API user') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('sightengine_api_user') is-invalid @enderror" id="setting-sightengine-api-user" required wire:model="sightengine_api_user">
                                            @error('sightengine_api_user')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-sightengine-api-secret">{{ __('API secret') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('sightengine_api_secret') is-invalid @enderror" id="setting-sightengine-api-secret" required type="password" wire:model="sightengine_api_secret">
                                            @error('sightengine_api_secret')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-sightengine-continuous">{{ __('Continuous?') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <div class="custom-control custom-switch mt-sm-2">
                                                <input class="custom-control-input @error('sightengine_continuous') is-invalid @enderror" id="setting-sightengine-continuous" type="checkbox" wire:model="sightengine_continuous" value="1">
                                                <label class="custom-control-label" for="setting-sightengine-continuous">{{ __('Yes') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif ($tab === 'queue')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-queue-default">{{ __('Driver') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('queue_default') is-invalid @enderror" id="setting-queue-default" required wire:model="queue_default">
                                            <option value="sync">{{ __('Synchronous') }}</option>
                                            <option value="redis">{{ __('Redis') }}</option>
                                        </select>
                                        @error('queue_default')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted form-text">
                                            {{ __('Setting this to "redis" allows server to use background job queues for various time consuming tasks and enhance response times.') }}
                                            {{ __('It requires Redis to be installed and a worker manager e.g., Supervisor configured to actually run the job queue.') }}
                                        </small>
                                    </div>
                                </div>
                            @elseif ($tab === 'referral')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-referral-enabled">{{ __('Enabled?') }} <span class="text-danger">&ast;</span></label>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-switch mt-sm-2">
                                            <input class="custom-control-input @error('referral_enabled') is-invalid @enderror" id="setting-referral-enabled" type="checkbox" wire:model="referral_enabled" value="1">
                                            <label class="custom-control-label" for="setting-referral-enabled">{{ __('Yes') }}</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($referral_enabled)
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-referral-reward">{{ __('Reward') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input class="form-control @error('referral_reward') is-invalid @enderror" id="setting-referral-reward" required wire:model="referral_reward">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __('Credits') }}</span>
                                                </div>
                                            </div>
                                            @error('referral_reward')
                                                <div class="is-invalid" style="display: none"></div>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('This is the number of credits a user will earn on every successful referral i.e., new registration.') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            @elseif ($tab === 'sms')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-otp-service">
                                        {{ __('Service') }} <span class="text-danger">&ast;</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('otp_service') is-invalid @enderror" id="setting-otp-service" required wire:model="otp_service">
                                            <option value="">{{ __('Select') }}&hellip;</option>
                                            <option value="firebase">{{ __('Firebase') }}</option>
                                            <option value="msg91">{{ __('MSG91') }}</option>
                                            <option value="twilio">{{ __('Twilio') }}</option>
                                        </select>
                                        @error('otp_service')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if ($otp_service === 'msg91')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-msg91-key">{{ __('Key') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('msg91_key') is-invalid @enderror" id="setting-msg91-key" required wire:model="msg91_key">
                                            @error('msg91_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @elseif ($otp_service === 'twilio')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-twilio-sid">{{ __('SID') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('twilio_sid') is-invalid @enderror" id="setting-twilio-sid" required wire:model="twilio_sid">
                                            @error('twilio_sid')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-twilio-token">{{ __('Token') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('twilio_token') is-invalid @enderror" id="setting-twilio-token" required wire:model="twilio_token">
                                            @error('twilio_token')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-twilio-verify-sid">{{ __('Verify SID') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('twilio_verify_sid') is-invalid @enderror" id="setting-twilio-verify-sid" required wire:model="twilio_verify_sid">
                                            @error('twilio_verify_sid')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @elseif ($tab === 'social')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-facebook-app-id">{{ __('Facebook app ID') }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('facebook_app_id') is-invalid @enderror" id="setting-facebook-app-id" wire:model="facebook_app_id">
                                        @error('facebook_app_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-facebook-app-secret">{{ __('Facebook app secret') }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('facebook_app_secret') is-invalid @enderror" id="setting-facebook-app-secret" type="password" wire:model="facebook_app_secret">
                                        @error('facebook_app_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-google-client-id">{{ __('Google client ID') }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('google_client_id') is-invalid @enderror" id="setting-google-client-id" wire:model="google_client_id">
                                        @error('google_client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('This must be client ID of the OAuth 2.0 client with type "3" in the google-services.json file downloaded from Firebase console.') }}
                                        </small>
                                    </div>
                                </div>
                            @elseif ($tab === 'storage')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-filesystems-cloud">
                                        {{ __('Driver') }} <span class="text-danger">&ast;</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control @error('filesystems_cloud') is-invalid @enderror" id="setting-filesystems-cloud" required wire:model="filesystems_cloud">
                                            <option value="public">{{ __('Public') }}</option>
                                            <option value="s3">{{ __('S3 or S3-compatible') }}</option>
                                            <option value="gcs">{{ __('Google Cloud Storage') }}</option>
                                        </select>
                                        @error('filesystems_cloud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if ($filesystems_cloud === 's3')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-aws-access-key-id">{{ __('Key ID & secret') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8 col-md-4">
                                            <div class="mb-3 mb-md-0">
                                                <input class="form-control @error('aws_access_key_id') is-invalid @enderror" id="setting-aws-access-key-id" required wire:model="aws_access_key_id">
                                                @error('aws_access_key_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-md-4 offset-sm-4 offset-md-0">
                                            <!--suppress HtmlFormInputWithoutLabel -->
                                            <input class="form-control @error('aws_secret_access_key') is-invalid @enderror" id="setting-aws-secret-access-key" type="password" required wire:model="aws_secret_access_key">
                                            @error('aws_secret_access_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-aws-bucket">{{ __('Bucket name') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('aws_bucket') is-invalid @enderror" id="setting-aws-bucket" required wire:model="aws_bucket">
                                            @error('aws_bucket')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-aws-default-region">{{ __('Region code') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('aws_default_region') is-invalid @enderror" id="setting-aws-default-region" required wire:model="aws_default_region">
                                            @error('aws_default_region')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('The AWS region code for above bucket e.g., ap-south-1.') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-aws-endpoint">{{ __('Endpoint') }}</label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('aws_endpoint') is-invalid @enderror" id="setting-aws-endpoint" wire:model="aws_endpoint">
                                            @error('aws_endpoint')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('This is required when using the S3 driver with a S3-compatible service other than AWS.') }}
                                            </small>
                                        </div>
                                    </div>
                                @elseif ($filesystems_cloud === 'gcs')
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-gcs-key-file">{{ __('Key file') }}</label>
                                        <div class="col-sm-8">
                                            <div class="custom-file">
                                                <input class="custom-file-input @error('gcs_key_file') is-invalid @enderror" id="setting-gcs-key-file" type="file" wire:model="gcs_key_file">
                                                <label class="custom-file-label" for="setting-gcs-key-file">
                                                    @if ($gcs_key_file)
                                                        {{ 'temporary.'.$gcs_key_file->extension() }}
                                                    @else
                                                        {{ __('Choose file') }}&hellip;
                                                    @endif
                                                </label>
                                                @error('gcs_key_file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="text-muted form-text" wire:loading wire:target="gcs_key_file">
                                                {{ __('Uploading') }}&hellip;
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-gcs-project-id">{{ __('Project ID') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('gcs_project_id') is-invalid @enderror" id="setting-gcs-project-id" required wire:model="gcs_project_id">
                                            @error('gcs_project_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="setting-gcs-bucket">{{ __('Bucket name') }} <span class="text-danger">&ast;</span></label>
                                        <div class="col-sm-8">
                                            <input class="form-control @error('gcs_bucket') is-invalid @enderror" id="setting-gcs-bucket" required wire:model="gcs_bucket">
                                            @error('gcs_bucket')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="setting-cdn-url">{{ __('CDN prefix') }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('cdn_url') is-invalid @enderror" id="setting-cdn-url" type="url" wire:model="cdn_url">
                                        @error('cdn_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('This is the URL prefix e.g., https://e2nztjqkys7ad2bq.cloudfront.net/ provided by your CDN service.') }}
                                            {{ __('It must end with a / (slash).') }}
                                        </small>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4">
                                    <button class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i> {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-right mb-0">
                    <strong>{{ __('Revision') }}</strong>:
                    <abbr data-toggle="tooltip" title="{{ config('fixtures.git_commit') }}">
                        {{ substr(config('fixtures.git_commit'), 0, 7) }}
                    </abbr>
                </p>
            </div>
        </div>
    </div>
</div>
