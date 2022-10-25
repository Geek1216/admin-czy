<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class SettingsUpdate extends Component
{
    use WithFileUploads;

    // Generate
    public $api_key;
    public $api_key_enabled;
    public $ranking_algorithm;

    // Live-streaming
    public $live_streaming_enabled;
    public $live_streaming_service;
    public $agora_app_id;
    public $agora_app_certificate;

    // Gifts
    public $gifts_enabled;
    public $payment_currency;
    public $payment_gateway;
    public $play_console_credentials;
    public $play_store_package_name;
    public $bitpay_token;
    public $instamojo_client_id;
    public $instamojo_client_secret;
    public $paypal_client_id;
    public $paypal_client_secret;
    public $razorpay_key_id;
    public $razorpay_key_secret;
    public $stripe_publishable_key;
    public $stripe_secret_key;

    // Cache
    public $cache_default;

    // Email
    public $mail_driver;
    public $mail_from_name;
    public $mail_from_address;
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    public $mailgun_domain;
    public $mailgun_secret;

    // Firebase
    public $firebase_credentials;
    public $firebase_dynamic_links_domain;
    public $firebase_package_name;

    // NSFW
    public $screening_service;
    public $sightengine_api_user;
    public $sightengine_api_secret;
    public $sightengine_continuous;

    // Queue
    public $queue_default;

    // Referral
    public $referral_enabled;
    public $referral_reward;

    // SMS
    public $otp_service;
    public $msg91_key;
    public $twilio_sid;
    public $twilio_token;
    public $twilio_verify_sid;

    // Social
    public $facebook_app_id;
    public $facebook_app_secret;
    public $google_client_id;

    // Storage
    public $filesystems_cloud;
    public $aws_access_key_id;
    public $aws_secret_access_key;
    public $aws_bucket;
    public $aws_default_region;
    public $aws_endpoint;
    public $gcs_key_file;
    public $gcs_project_id;
    public $gcs_bucket;
    public $cdn_url;

    public $tab = 'api';

    public function change(string $tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        // API
        $this->api_key = setting('api_key', config('fixtures.api_key'));
        $this->api_key_enabled = $this->api_key && setting('api_key_enabled', false);
        $this->ranking_algorithm = setting('ranking_algorithm', config('fixtures.ranking_algorithm'));
        // Cache
        $this->cache_default = setting('cache_default', config('cache.default'));
        // Email
        $this->mail_driver = setting('mail_driver', config('mail.driver'));
        $this->mail_from_name = setting('mail_from_name', config('mail.from.name'));
        $this->mail_from_address = setting('mail_from_address', config('mail.from.address'));
        $this->mail_host = setting('mail_host', config('mail.host'));
        $this->mail_port = setting('mail_port', config('mail.port'));
        $this->mail_username = setting('mail_username', config('mail.username'));
        $this->mail_password = setting('mail_password', config('mail.password'));
        $this->mail_encryption = setting('mail_encryption', config('mail.encryption'));
        $this->mailgun_domain = setting('mailgun_domain', config('services.mailgun.domain'));
        $this->mailgun_secret = setting('mailgun_secret', config('services.mailgun.secret'));
        // Firebase
        $this->firebase_package_name = setting('firebase_package_name', config('services.firebase.package_name'));
        $this->firebase_dynamic_links_domain = setting('firebase_dynamic_links_domain', config('services.firebase.dynamic_links_domain'));
        // Gifts
        $this->live_streaming_enabled = setting('live_streaming_enabled', false);
        $this->live_streaming_service = setting('live_streaming_service');
        $this->agora_app_id = setting('agora_app_id');
        $this->agora_app_certificate = setting('agora_app_certificate');
        // Gifts
        $this->gifts_enabled = setting('gifts_enabled', config('fixtures.gifts_enabled'));
        $this->payment_currency = setting('payment_currency', config('fixtures.payment_currency'));
        $this->payment_gateway = setting('payment_gateway', config('fixtures.payment_gateway'));
        $this->play_store_package_name = setting('play_store_package_name', config('services.google_play.package_name'));
        $this->bitpay_token = setting('bitpay_token', config('services.bitpay.token'));
        $this->instamojo_client_id = setting('instamojo_client_id', config('services.instamojo.client_id'));
        $this->instamojo_client_secret = setting('instamojo_client_secret', config('services.instamojo.client_secret'));
        $this->paypal_client_id = setting('instamojo_client_id', config('services.paypal.client_id'));
        $this->paypal_client_secret = setting('instamojo_client_secret', config('services.paypal.client_secret'));
        $this->razorpay_key_id = setting('razorpay_key_id', config('services.razorpay.key_id'));
        $this->razorpay_key_secret = setting('razorpay_key_secret', config('services.razorpay.key_secret'));
        $this->stripe_publishable_key = setting('stripe_publishable_key', config('services.stripe.publishable_key'));
        $this->stripe_secret_key = setting('stripe_secret_key', config('services.stripe.secret_key'));
        // NSFW
        $this->screening_service = setting('screening_service', config('fixtures.screening_service'));
        $this->sightengine_api_user = setting('sightengine_api_user', config('services.sightengine.api_user'));
        $this->sightengine_api_secret = setting('sightengine_api_secret', config('services.sightengine.api_secret'));
        $this->sightengine_continuous = setting('sightengine_continuous', config('services.sightengine.continuous'));
        // Queue
        $this->queue_default = setting('queue_default', config('queue.default'));
        // Referral
        $this->referral_enabled = setting('referral_enabled', config('fixtures.referral_enabled'));
        $this->referral_reward = setting('referral_reward', config('fixtures.referral_reward'));
        // SMS
        $this->otp_service = setting('otp_service', config('fixtures.otp_service'));
        $this->msg91_key = setting('msg91_key', config('msg91.key'));
        $this->twilio_sid = setting('twilio_sid', config('services.twilio.sid'));
        $this->twilio_token = setting('twilio_token', config('services.twilio.auth_token'));
        $this->twilio_verify_sid = setting('twilio_verify_sid', config('services.twilio.verify_sid'));
        // Social
        $this->facebook_app_id = setting('facebook_app_id', config('services.facebook.app_id'));
        $this->facebook_app_secret = setting('facebook_app_secret', config('services.facebook.app_secret'));
        $this->google_client_id = setting('google_client_id', config('services.google.client_id'));
        // Storage
        $this->filesystems_cloud = setting('filesystems_cloud', config('filesystems.cloud'));
        $this->aws_access_key_id = setting('aws_access_key_id', config('filesystems.disks.s3.key'));
        $this->aws_secret_access_key = setting('aws_secret_access_key', config('filesystems.disks.s3.secret'));
        $this->aws_bucket = setting('aws_bucket', config('filesystems.disks.s3.bucket'));
        $this->aws_default_region = setting('aws_default_region', config('filesystems.disks.s3.region'));
        $this->aws_endpoint = setting('aws_endpoint', config('filesystems.disks.s3.endpoint'));
        $this->gcs_project_id = setting('gcs_project_id', config('filesystems.disks.gcs.project_id'));
        $this->gcs_bucket = setting('gcs_bucket', config('filesystems.disks.gcs.bucket'));
        $this->cdn_url = setting('cdn_url', config('fixtures.cdn_url'));
    }

    public function regenerate()
    {
        $this->api_key = Str::upper(Str::random(32));
    }

    public function render()
    {
        return view('livewire.settings-update');
    }

    public function update()
    {
        $gcs_key_file = setting('gcs_key_file', config('filesystems.disks.gcs.key_file'));
        $play_console_credentials = setting('play_console_credentials', config('services.google_play.credentials'));
        $data = $this->validate([
            // General
            'api_key' => ['nullable', 'required_if:api_key_enabled,1', 'string', 'size:32', 'regex:/^[A-Z\d]{32}$/'],
            'api_key_enabled' => ['sometimes', 'boolean'],
            'ranking_algorithm' => ['required', 'string', 'in:sequential,random'],
            // Live-streaming
            'live_streaming_enabled' => ['sometimes', 'boolean'],
            'live_streaming_service' => ['nullable', 'required_if:live_streaming_enabled,1', 'in:agora'],
            'agora_app_id' => ['nullable', 'required_if:live_streaming_service,agora', 'max:255'],
            'agora_app_certificate' => ['nullable', 'required_if:live_streaming_service,agora', 'max:255'],
            // Cache
            'cache_default' => ['required', 'string', 'in:file,redis'],
            // Firebase
            'firebase_credentials' => ['nullable', 'file', 'max:100'],
            'firebase_package_name' => ['nullable', 'string', 'max:255'],
            'firebase_dynamic_links_domain' => ['nullable', 'string', 'max:255'],
            // Email
            'mail_driver' => ['required', 'string', 'in:sendmail,smtp,mailgun'],
            'mail_from_name' => ['required', 'string'],
            'mail_from_address' => ['required', 'string', 'email'],
            'mail_host' => ['nullable', 'required_if:mail_driver,smtp', 'string', 'max:255'],
            'mail_port' => ['nullable', 'required_if:mail_driver,smtp', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'required_if:mail_driver,smtp', 'string', 'in:ssl,tls'],
            'mailgun_domain' => ['nullable', 'required_if:mail_driver,mailgun', 'string', 'max:255'],
            'mailgun_secret' => ['nullable', 'required_if:mail_driver,mailgun', 'string', 'max:255'],
            // Gifts
            'gifts_enabled' => ['sometimes', 'boolean'],
            'payment_currency' => ['nullable', 'string', 'required_if:gifts_enabled,1', 'regex:/^[A-Z]{3}$/'],
            'payment_gateway' => ['nullable', 'string', 'required_if:gifts_enabled,1', 'in:play_store,bitpay,instamojo,paypal,razorpay,stripe'],
            'bitpay_token' => ['nullable', 'string', 'required_if:payment_gateway,bitpay', 'max:255'],
            'play_console_credentials' => [
                'nullable',
                $play_console_credentials && is_file($play_console_credentials) ? 'nullable' : 'required_if:payment_gateway,play_store',
                'file',
                'max:100',
            ],
            'play_store_package_name' => ['nullable', 'required_if:payment_gateway,play_store', 'string', 'max:255'],
            'instamojo_client_id' => ['nullable', 'string', 'required_if:payment_gateway,instamojo', 'max:255'],
            'instamojo_client_secret' => ['nullable', 'string', 'required_if:payment_gateway,instamojo', 'max:255'],
            'paypal_client_id' => ['nullable', 'string', 'required_if:payment_gateway,paypal', 'max:255'],
            'paypal_client_secret' => ['nullable', 'string', 'required_if:payment_gateway,paypal', 'max:255'],
            'razorpay_key_id' => ['nullable', 'string', 'required_if:payment_gateway,razorpay', 'max:255'],
            'razorpay_key_secret' => ['nullable', 'string', 'required_if:payment_gateway,razorpay', 'max:255'],
            'stripe_publishable_key' => ['nullable', 'string', 'required_if:payment_gateway,stripe', 'max:255'],
            'stripe_secret_key' => ['nullable', 'string', 'required_if:payment_gateway,stripe', 'max:255'],
            // NSFW
            'screening_service' => ['nullable', 'string', 'in:aws,gcp,sightengine'],
            'sightengine_api_user' => ['nullable', 'required_if:screening_service,sightengine', 'digits_between:5,15'],
            'sightengine_api_secret' => ['nullable', 'required_if:screening_service,sightengine', 'string', 'size:20'],
            'sightengine_continuous' => ['nullable', 'boolean'],
            // Queue
            'queue_default' => ['required', 'string', 'in:sync,redis'],
            // Referral
            'referral_enabled' => ['sometimes', 'boolean'],
            'referral_reward' => ['nullable', 'required_with:referral_enabled', 'integer', 'min:0'],
            // SMS
            'otp_service' => ['nullable', 'string', 'in:firebase,msg91,twilio'],
            'msg91_key' => ['nullable', 'required_if:otp_service,msg91', 'string', 'max:255'],
            'twilio_sid' => ['nullable', 'required_if:otp_service,twilio', 'string', 'regex:/^[a-zA-Z\d]{34}$/'],
            'twilio_token' => ['nullable', 'required_if:otp_service,twilio', 'string', 'regex:/^[a-z\d]{32}$/'],
            'twilio_verify_sid' => ['nullable', 'required_if:otp_service,twilio', 'string', 'regex:/^[a-zA-Z\d]{34}$/'],
            // Social
            'facebook_app_id' => ['nullable', 'required_with:facebook_app_secret', 'digits_between:10,20'],
            'facebook_app_secret' => ['nullable', 'required_with:facebook_app_id', 'size:32'],
            'google_client_id' => ['nullable', 'string', 'max:255'],
            // Storage
            'filesystems_cloud' => ['required', 'string', 'in:public,s3,gcs'],
            'aws_access_key_id' => ['nullable', 'required_if:filesystems_cloud,s3', 'string', 'max:255'],
            'aws_secret_access_key' => ['nullable', 'required_if:filesystems_cloud,s3', 'string', 'max:255'],
            'aws_default_region' => ['nullable', 'required_if:filesystems_cloud,s3', 'string', 'max:255'],
            'aws_bucket' => ['nullable', 'required_if:filesystems_cloud,s3', 'string', 'max:255'],
            'aws_endpoint' => ['nullable', 'string', 'max:255'],
            'gcs_key_file' => [
                'nullable',
                $gcs_key_file && is_file($gcs_key_file) ? 'nullable' : 'required_if:filesystems_cloud,gcs',
                'file',
                'max:100',
            ],
            'gcs_project_id' => ['nullable', 'required_if:filesystems_cloud,gcs', 'string', 'max:255'],
            'gcs_bucket' => ['nullable', 'required_if:filesystems_cloud,gcs', 'string', 'max:255'],
            'cdn_url' => ['nullable', 'url'],
        ]);
        $data['api_key_enabled'] = (bool) $data['api_key_enabled'];
        if (empty($data['firebase_credentials'])) {
            unset($data['firebase_credentials']);
        } else {
            /** @var TemporaryUploadedFile $firebase_credentials */
            $firebase_credentials = $data['firebase_credentials'];
            $target = storage_path('firebase-credentials.json');
            file_put_contents($target, $firebase_credentials->readStream());
            $data['firebase_credentials'] = $target;
        }
        if (empty($data['play_console_credentials'])) {
            unset($data['play_console_credentials']);
        } else {
            /** @var TemporaryUploadedFile $play_console_credentials */
            $play_console_credentials = $data['play_console_credentials'];
            $target = storage_path('play-console-credentials.json');
            file_put_contents($target, $play_console_credentials->readStream());
            $data['play_console_credentials'] = $target;
        }
        if (empty($data['gcs_key_file'])) {
            unset($data['gcs_key_file']);
        } else {
            /** @var TemporaryUploadedFile $gcs_key_file */
            $gcs_key_file = $data['gcs_key_file'];
            $target = storage_path('gcs-key-file.json');
            file_put_contents($target, $gcs_key_file->readStream());
            $data['gcs_key_file'] = $target;
        }
        setting($data);
        setting()->save();
        flash()->info(__('Settings have been successfully updated.'));
        $this->redirect(route('settings.update'));
    }
}
