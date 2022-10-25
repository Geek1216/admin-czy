@extends('layouts.auth', ['main_columns' => 'col-md-10 col-lg-9 col-xl-8'])

@section('meta')
    <title>{{ __('Install') }} &raquo; {{ __('Configure') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-primary">{{ __('Configure') }}</h5>
            <p class="card-text">
                {{ __('Please enter configuration values carefully or else it could result in installation failure.') }}
            </p>
        </div>
        <form action="" enctype="multipart/form-data" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="form-group">
                    <label for="configure-purchase-code">
                        {{ __('Purchase code') }} <span class="text-danger">&ast;</span>
                    </label>
                    <input class="form-control @error('PURCHASE_CODE') is-invalid @enderror" id="configure-purchase-code" name="PURCHASE_CODE" required value="{{ old('PURCHASE_CODE', config('fixtures.purchase_code')) }}">
                    @error('PURCHASE_CODE')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">{{ __('You can find this in your license certificate received from Envato.') }}</small>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="configure-app-name">
                                {{ __('Name') }} <span class="text-danger">&ast;</span>
                            </label>
                            <input class="form-control @error('APP_NAME') is-invalid @enderror" id="configure-app-name" name="APP_NAME" required value="{{ old('APP_NAME', config('app.name')) }}">
                            @error('APP_NAME')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('This is the name of the app shown everywhere in admin panel and outgoing emails.') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="configure-app-url">
                                {{ __('URL') }} <span class="text-danger">&ast;</span>
                            </label>
                            <input class="form-control @error('APP_URL') is-invalid @enderror" id="configure-app-url" name="APP_URL" required value="{{ old('APP_URL', rtrim(url('/'), '/')) }}">
                            @error('APP_URL')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-md-0">
                            <label for="configure-app-timezone">
                                {{ __('Timezone') }} <span class="text-danger">&ast;</span>
                            </label>
                            <select class="form-control @error('APP_TIMEZONE') is-invalid @enderror" id="configure-app-timezone" name="APP_TIMEZONE" required>
                                @php
                                    $old_timezone = old('APP_TIMEZONE', config('app.timezone'));
                                @endphp
                                @foreach (timezone_identifiers_list() as $timezone)
                                    <option value="{{ $timezone }}" @if ($timezone === $old_timezone) selected @endif>{{ $timezone }}</option>
                                @endforeach
                            </select>
                            @error('APP_TIMEZONE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="configure-db-host">
                                {{ __('Database host & port') }} <span class="text-danger">&ast;</span>
                            </label>
                            <div class="input-group @if ($errors->has('DB_HOST') || $errors->has('DB_PORT')) is-invalid @endif">
                                <input class="form-control @error('DB_HOST') is-invalid @enderror" id="configure-db-host" name="DB_HOST" required value="{{ old('DB_HOST', config('database.connections.mysql.host')) }}">
                                <input class="form-control @error('DB_PORT') is-invalid @enderror" id="configure-db-port" name="DB_PORT" required type="number" value="{{ old('DB_PORT', config('database.connections.mysql.port')) }}">
                            </div>
                            @error('DB_HOST')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('DB_PORT')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="configure-db-database">
                                {{ __('Database name') }} <span class="text-danger">&ast;</span>
                            </label>
                            <input class="form-control @error('DB_DATABASE') is-invalid @enderror" id="configure-db-database" name="DB_DATABASE" required value="{{ old('DB_DATABASE', config('database.connections.mysql.database')) }}">
                            @error('DB_DATABASE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label for="configure-db-username">
                                {{ __('Database username & password') }} <span class="text-danger">&ast;</span>
                            </label>
                            <div class="input-group @if ($errors->has('DB_USERNAME') || $errors->has('DB_PASSWORD')) is-invalid @endif">
                                <input class="form-control @error('DB_USERNAME') is-invalid @enderror" id="configure-db-username" name="DB_USERNAME" required value="{{ old('DB_USERNAME', config('database.connections.mysql.username')) }}">
                                <input class="form-control @error('DB_PASSWORD') is-invalid @enderror" id="configure-db-password" name="DB_PASSWORD" required type="password">
                            </div>
                            @error('DB_USERNAME')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('DB_PASSWORD')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="configure-facebook-app-id">{{ __('Facebook app ID') }}</label>
                            <input class="form-control @error('FACEBOOK_APP_ID') is-invalid @enderror" id="configure-facebook-app-id" name="FACEBOOK_APP_ID" value="{{ old('FACEBOOK_APP_ID', config('services.facebook.app_id')) }}">
                            @error('FACEBOOK_APP_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('You need to create a Facebook app first to get this value.') }}</small>
                        </div>
                        <div class="form-group mb-md-0">
                            <label for="configure-facebook-app-secret">{{ __('Facebook app secret') }}</label>
                            <input class="form-control @error('FACEBOOK_APP_SECRET') is-invalid @enderror" id="configure-facebook-app-secret" name="FACEBOOK_APP_SECRET" value="{{ old('FACEBOOK_APP_SECRET', config('services.facebook.app_secret')) }}">
                            @error('FACEBOOK_APP_SECRET')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('You need to create a Facebook app first to get this value.') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label for="configure-google-client-id">{{ __('Google client ID') }}</label>
                            <input class="form-control @error('GOOGLE_CLIENT_ID') is-invalid @enderror" id="configure-google-client-id" name="GOOGLE_CLIENT_ID" value="{{ old('GOOGLE_CLIENT_ID', config('services.google.client_id')) }}">
                            @error('GOOGLE_CLIENT_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                {{ __('This must be client ID of the "Web Application" OAuth 2.0 client associated with your Firebase project.') }}
                                {{ __('You can find this in Google Cloud console after your Firebase project and enabling Google sign-in method.') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-md-0">
                            <label for="configure-firebase-credentials">
                                {{ __('Firebase credentials') }} <span class="text-danger">&ast;</span>
                            </label>
                            <div class="custom-file">
                                <input class="custom-file-input @error('FIREBASE_CREDENTIALS') is-invalid @enderror" id="configure-firebase-credentials" name="FIREBASE_CREDENTIALS" required type="file">
                                <label class="custom-file-label" for="configure-firebase-credentials">{{ __('Choose file') }}&hellip;</label>
                                @error('FIREBASE_CREDENTIALS')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('This must be the key file (in JSON format) associated with your Firebase project service account.') }}
                                    {{ __('You can generate/download it from Firebase console.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @php
                            $old_otp_service = old('OTP_SERVICE', config('fixtures.otp_service'))
                        @endphp
                        <div class="form-group mb-0">
                            <label for="configure-otp-service">{{ __('OTP service') }} <span class="text-danger">&ast;</span></label>
                            <select class="form-control @error('OTP_SERVICE') is-invalid @enderror" id="configure-otp-service" name="OTP_SERVICE" required>
                                <option value="firebase" @if ($old_otp_service === 'firebase') selected @endif>{{ __('Firebase') }}</option>
                                <option value="twilio" @if ($old_otp_service === 'twilio') selected @endif>{{ __('Twilio') }}</option>
                                <option value="msg91" @if ($old_otp_service === 'msg91') selected @endif>{{ __('MSG91') }}</option>
                            </select>
                            @error('OTP_SERVICE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 @if ($old_otp_service !== 'twilio') d-none @endif" data-toggle-if="#configure-otp-service,twilio,d-none">
                            <div class="form-group">
                                <label for="configure-twilio-sid">{{ __('Twilio SID') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('TWILIO_SID') is-invalid @enderror" id="configure-twilio-sid" name="TWILIO_SID" value="{{ old('TWILIO_SID', config('services.twilio.sid')) }}">
                                @error('TWILIO_SID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="configure-twilio-auth-token">{{ __('Twilio auth token') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('TWILIO_AUTH_TOKEN') is-invalid @enderror" id="configure-twilio-auth-token" name="TWILIO_AUTH_TOKEN" value="{{ old('TWILIO_AUTH_TOKEN', config('services.twilio.auth_token')) }}">
                                @error('TWILIO_AUTH_TOKEN')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="configure-twilio-verify-sid">{{ __('Twilio verify SID') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('TWILIO_VERIFY_SID') is-invalid @enderror" id="configure-twilio-verify-sid" name="TWILIO_VERIFY_SID" value="{{ old('TWILIO_VERIFY_SID', config('services.twilio.verify_sid')) }}">
                                @error('TWILIO_VERIFY_SID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 @if ($old_otp_service !== 'msg91') d-none @endif" data-toggle-if="#configure-otp-service,msg91,d-none">
                            <div class="form-group mb-0">
                                <label for="configure-msg91-auth-key">{{ __('MSG91 auth key') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('MSG91_AUTH_KEY') is-invalid @enderror" id="configure-msg91-auth-key" name="MSG91_AUTH_KEY" value="{{ old('MSG91_AUTH_KEY', config('msg91.auth_key')) }}">
                                @error('OTP_SERVICE')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-6">
                        @php
                            $old_filesystem_cloud = old('FILESYSTEM_CLOUD', config('filesystems.cloud'))
                        @endphp
                        <div class="form-group mb-md-0">
                            <label for="configure-filesystem-cloud">{{ __('Filesystem driver') }} <span class="text-danger">&ast;</span></label>
                            <select class="form-control @error('FILESYSTEM_CLOUD') is-invalid @enderror" id="configure-filesystem-cloud" name="FILESYSTEM_CLOUD" required>
                                <option value="public" @if ($old_filesystem_cloud === 'public') selected @endif>{{ __('Public') }}</option>
                                <option value="s3" @if ($old_filesystem_cloud === 's3') selected @endif>{{ __('S3, DigitalOcean or Backblaze') }}</option>
                                <option value="gcs" @if ($old_filesystem_cloud === 'gcs') selected @endif>{{ __('Google Cloud') }}</option>
                            </select>
                            @error('FILESYSTEM_CLOUD')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 @if ($old_filesystem_cloud !== 's3') d-none @endif" data-toggle-if="#configure-filesystem-cloud,s3,d-none">
                            <div class="form-group">
                                <label for="configure-aws-access-key-id">{{ __('Key ID & secret') }} <span class="text-danger">&ast;</span></label>
                                <div class="input-group">
                                    <input class="form-control @error('AWS_ACCESS_KEY_ID') is-invalid @enderror" id="configure-aws-access-key-id" name="AWS_ACCESS_KEY_ID" value="{{ old('AWS_ACCESS_KEY_ID', config('filesystems.disks.s3.key')) }}">
                                    <input class="form-control @error('AWS_SECRET_ACCESS_KEY') is-invalid @enderror" id="configure-aws-secret-access-key" name="AWS_SECRET_ACCESS_KEY" value="{{ old('AWS_SECRET_ACCESS_KEY', config('filesystems.disks.s3.secret')) }}">
                                </div>
                                @error('AWS_ACCESS_KEY_ID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('AWS_SECRET_ACCESS_KEY')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('For DigitalOcean, you can generate these in Account > API > Spaces access keys.') }}
                                    {{ __('For Backblaze, you can create these in App Keys > Add a New Application Key.') }}
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="configure-aws-bucket">{{ __('Bucket or Space') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('AWS_BUCKET') is-invalid @enderror" id="configure-aws-bucket" name="AWS_BUCKET" value="{{ old('AWS_BUCKET', config('filesystems.disks.s3.bucket')) }}">
                                @error('AWS_BUCKET')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="configure-aws-default-region">{{ __('Region') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('AWS_DEFAULT_REGION') is-invalid @enderror" id="configure-aws-default-region" name="AWS_DEFAULT_REGION" value="{{ old('AWS_DEFAULT_REGION', config('filesystems.disks.s3.region')) }}">
                                @error('AWS_DEFAULT_REGION')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="configure-aws-endpoint">{{ __('Endpoint') }}</label>
                                <input class="form-control @error('AWS_ENDPOINT') is-invalid @enderror" id="configure-aws-endpoint" name="AWS_ENDPOINT" value="{{ old('AWS_ENDPOINT', config('filesystems.disks.s3.endpoint')) }}">
                                @error('AWS_ENDPOINT')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('This is only required when using S3 driver with DigitalOcean or Backblaze and must start with https://...') }}
                                    {{ __('For usage with S3 directly, leave it blank.') }}
                                </small>
                            </div>
                        </div>
                        <div class="mt-3 @if ($old_filesystem_cloud !== 'gcs') d-none @endif" data-toggle-if="#configure-filesystem-cloud,gcs,d-none">
                            <div class="form-group">
                                <label for="configure-google-application-credentials">
                                    {{ __('Google application credentials') }} <span class="text-danger">&ast;</span>
                                </label>
                                <div class="custom-file">
                                    <input class="custom-file-input @error('GOOGLE_APPLICATION_CREDENTIALS') is-invalid @enderror" id="configure-google-application-credentials" name="GOOGLE_APPLICATION_CREDENTIALS" type="file">
                                    <label class="custom-file-label" for="configure-google-application-credentials">{{ __('Choose file') }}&hellip;</label>
                                    @error('GOOGLE_APPLICATION_CREDENTIALS')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="configure-gcs-project-id">{{ __('GCS project ID') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('GOOGLE_CLOUD_PROJECT_ID') is-invalid @enderror" id="configure-gcs-project-id" name="GOOGLE_CLOUD_PROJECT_ID" value="{{ old('GOOGLE_CLOUD_PROJECT_ID', config('filesystems.disks.gcs.project_id')) }}">
                                @error('GOOGLE_CLOUD_PROJECT_ID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('This is the unique project ID for your Google Cloud project e.g., muly-1234567890.') }}
                                </small>
                            </div>
                            <div class="form-group mb-0">
                                <label for="configure-gcs-bucket">{{ __('GCS bucket') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('GOOGLE_CLOUD_STORAGE_BUCKET') is-invalid @enderror" id="configure-gcs-bucket" name="GOOGLE_CLOUD_STORAGE_BUCKET" value="{{ old('GOOGLE_CLOUD_STORAGE_BUCKET', config('filesystems.disks.gcs.bucket')) }}">
                                @error('GOOGLE_CLOUD_STORAGE_BUCKET')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @php
                            $old_queue_connection = old('QUEUE_CONNECTION', config('queue.default'))
                        @endphp
                        <div class="form-group mb-0">
                            <label for="configure-queue-connection">{{ __('Queue driver') }} <span class="text-danger">&ast;</span></label>
                            <select class="form-control @error('QUEUE_CONNECTION') is-invalid @enderror" id="configure-queue-connection" name="QUEUE_CONNECTION" required>
                                <option value="sync" @if ($old_queue_connection === 'sync') selected @endif>{{ __('Synchronous') }}</option>
                                <option value="redis" @if ($old_queue_connection === 'redis') selected @endif>{{ __('Redis') }}</option>
                            </select>
                            @error('FILESYSTEM_CLOUD')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                {{ __('Setting it to "redis" allows server to use background job queues for various time consuming tasks and enhance response times.') }}
                                {{ __('But it requires Redis to be installed and running on server otherwise login and other requests that rely upon job queues would start to fail.') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-6">
                        @php
                            $old_mail_driver = old('MAIL_DRIVER', config('mail.driver'))
                        @endphp
                        <div class="form-group mb-md-0">
                            <label for="configure-mail-driver">{{ __('Mail driver') }} <span class="text-danger">&ast;</span></label>
                            <select class="form-control @error('MAIL_DRIVER') is-invalid @enderror" id="configure-mail-driver" name="MAIL_DRIVER" required>
                                <option value="sendmail" @if ($old_mail_driver === 'sendmail') selected @endif>{{ __('Sendmail') }}</option>
                                <option value="smtp" @if ($old_mail_driver === 'smtp') selected @endif>{{ __('SMTP') }}</option>
                                <option value="mailgun" @if ($old_mail_driver === 'mailgun') selected @endif>{{ __('Mailgun') }}</option>
                            </select>
                            @error('MAIL_DRIVER')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 @if ($old_mail_driver !== 'smtp') d-none @endif" data-toggle-if="#configure-mail-driver,smtp,d-none">
                            <div class="form-group">
                                <label for="configure-mail-host">{{ __('SMTP host') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('MAIL_HOST') is-invalid @enderror" id="configure-mail-host" name="MAIL_HOST" value="{{ old('MAIL_HOST', config('mail.host')) }}">
                                @error('MAIL_HOST')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="configure-mail-port">{{ __('SMTP port') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('MAIL_PORT') is-invalid @enderror" id="configure-mail-port" name="MAIL_PORT" type="number" value="{{ old('MAIL_PORT', config('mail.port')) }}">
                                @error('MAIL_PORT')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="configure-mail-username">{{ __('SMTP username') }}</label>
                                <input class="form-control @error('MAIL_USERNAME') is-invalid @enderror" id="configure-mail-username" name="MAIL_USERNAME" value="{{ old('MAIL_USERNAME', config('mail.username')) }}">
                                @error('MAIL_USERNAME')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="configure-mail-password">{{ __('SMTP password') }}</label>
                                <input class="form-control @error('MAIL_PASSWORD') is-invalid @enderror" id="configure-mail-password" name="MAIL_PASSWORD" type="password">
                                @error('MAIL_PASSWORD')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @php
                                $old_mail_encryption = old('MAIL_ENCRYPTION', config('mail.encryption'))
                            @endphp
                            <div class="form-group mb-md-0">
                                <label for="configure-mail-encryption">{{ __('SMTP encryption') }}</label>
                                <select class="form-control @error('MAIL_ENCRYPTION') is-invalid @enderror" id="configure-mail-encryption" name="MAIL_ENCRYPTION">
                                    <option value="" @if (empty($old_mail_encryption)) selected @endif>{{ __('None') }}</option>
                                    <option value="ssl" @if ($old_mail_encryption === 'ssl') selected @endif>{{ __('SSL') }}</option>
                                    <option value="tls" @if ($old_mail_encryption === 'tls') selected @endif>{{ __('TLS') }}</option>
                                </select>
                                @error('MAIL_ENCRYPTION')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 @if ($old_mail_driver !== 'mailgun') d-none @endif" data-toggle-if="#configure-mail-driver,mailgun,d-none">
                            <div class="form-group">
                                <label for="configure-mailgun-domain">{{ __('Mailgun domain') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('MAILGUN_DOMAIN') is-invalid @enderror" id="configure-mailgun-domain" name="MAILGUN_DOMAIN" value="{{ old('MAILGUN_DOMAIN', config('services.mailgun.domain')) }}">
                                @error('MAILGUN_DOMAIN')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('This is the domain you may have already verified with Mailgun e.g., mg.yourapp.com etc.') }}
                                </small>
                            </div>
                            <div class="form-group mb-0">
                                <label for="configure-mailgun-secret">{{ __('Mailgun secret') }} <span class="text-danger">&ast;</span></label>
                                <input class="form-control @error('MAILGUN_SECRET') is-invalid @enderror" id="configure-mailgun-secret" name="MAILGUN_SECRET" value="{{ old('MAILGUN_SECRET', config('services.mailgun.secret')) }}">
                                @error('MAILGUN_SECRET')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="btn-toolbar">
                    <a class="btn btn-outline-dark" href="{{ route('install.overview') }}">
                        <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
                    </a>
                    <button class="btn btn-primary ml-auto">
                        {{ __('Continue') }} <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
