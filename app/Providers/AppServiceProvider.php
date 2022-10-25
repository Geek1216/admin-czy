<?php

namespace App\Providers;

use App\Advertisement;
use App\Challenge;
use App\Clip;
use App\NotificationTemplate;
use App\Observers\AdvertisementObserver;
use App\Observers\ChallengeObserver;
use App\Observers\ClipObserver;
use App\Observers\NotificationTemplateObserver;
use App\Observers\PromotionObserver;
use App\Observers\SongObserver;
use App\Observers\StickerObserver;
use App\Observers\StickerSectionObserver;
use App\Observers\UserObserver;
use App\Observers\VerificationObserver;
use App\Promotion;
use App\Song;
use App\Sticker;
use App\StickerSection;
use App\Support\Visitor as VisitorImpl;
use App\User;
use App\Verification;
use Aws\Rekognition\RekognitionClient;
use BitPaySDKLight\Client as BitPay;
use CyrildeWit\EloquentViewable\Contracts\Visitor as VisitorContract;
use Facebook\Facebook;
use Google\Cloud\VideoIntelligence\V1\VideoIntelligenceServiceClient;
use Illuminate\Support\ServiceProvider;
use Instamojo\Instamojo;
use Laravel\Sanctum\Sanctum;
use Laravel\Telescope\Telescope;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use Razorpay\Api\Api as Razorpay;
use Sightengine\SightengineClient;
use Stripe\Stripe;
use Twilio\Rest\Client as Twilio;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BitPay::class, function () {
            return new BitPay(setting('bitpay_token', config('services.bitpay.token')));
        });
        $this->app->alias(BitPay::class, 'bitpay');
        $this->app->bind(Facebook::class, function () {
            return new Facebook([
                'app_id' => setting('facebook_app_id', config('services.facebook.app_id')),
                'app_secret' => setting('facebook_app_secret', config('services.facebook.app_secret')),
                'default_graph_version' => 'v2.10',
            ]);
        });
        $this->app->bind(\Google_Client::class, function () {
            return new \Google_Client([
                'client_id' => setting('google_client_id', config('services.google.client_id')),
            ]);
        });
        $this->app->bind(\Google_Service_AndroidPublisher::class, function () {
            $client = new \Google_Client();
            $client->setAuthConfig(
                setting('play_console_credentials', config('services.google_play.credentials')));
            $client->addScope('https://www.googleapis.com/auth/androidpublisher');
            return new \Google_Service_AndroidPublisher($client);
        });
        $this->app->alias(\Google_Service_AndroidPublisher::class, 'google_play_service');
        $this->app->bind(Instamojo::class, function () {
            return Instamojo::init('app', [
                'client_id' => setting('instamojo_client_id', config('services.instamojo.client_id')),
                'client_secret' => setting('instamojo_client_secret', config('services.instamojo.client_secret'))
            ]);
        });
        $this->app->alias(Instamojo::class, 'instamojo');
        $this->app->bind(PayPalHttpClient::class, function () {
            $environment = new ProductionEnvironment(
                setting('paypal_client_id', config('services.paypal.client_id')),
                setting('paypal_client_secret', config('services.paypal.client_secret'))
            );
            return new PayPalHttpClient($environment);
        });
        $this->app->alias(PayPalHttpClient::class, 'paypal');
        $this->app->bind(Razorpay::class, function () {
            return new Razorpay(
                setting('razorpay_key_id', config('services.razorpay.key_id')),
                setting('razorpay_key_secret', config('services.razorpay.key_secret')));
        });
        $this->app->alias(Razorpay::class, 'razorpay');
        $this->app->bind('path.public', function() {
            return base_path().'/../public_html';
        });
        $this->app->bind(RekognitionClient::class, function () {
            return new RekognitionClient([
                'credentials' => [
                    'key' => setting('aws_access_key_id', config('filesystems.disks.s3.key')),
                    'secret' => setting('aws_secret_access_key', config('filesystems.disks.s3.secret')),
                ],
                'region' => setting('aws_default_region', config('filesystems.disks.s3.region')),
                'version' => 'latest',
            ]);
        });
        $this->app->bind(SightengineClient::class, function () {
            return new SightengineClient(
                setting('sightengine_api_user', config('services.sightengine.api_user')),
                setting('sightengine_api_secret', config('services.sightengine.api_secret')));
        });
        $this->app->alias(SightengineClient::class, 'sightengine');
        $this->app->bind(Twilio::class, function () {
            return new Twilio(
                setting('twilio_sid', config('services.twilio.sid')),
                setting('twilio_token', config('services.twilio.auth_token')));
        });
        $this->app->alias(Twilio::class, 'twilio');
        $this->app->bind('twilio.verify', function () {
            return app(Twilio::class)
                ->verify->v2->services(setting('twilio_verify_sid', config('services.twilio.verify_sid')));
        });
        $this->app->bind(VideoIntelligenceServiceClient::class, function () {
            return new VideoIntelligenceServiceClient([
                'credentials' => setting('gcs_key_file', config('services.google_cloud.credentials')),
            ]);
        });
        $this->app->alias(VideoIntelligenceServiceClient::class, 'video_intelligence');
        $this->app->bind(VisitorContract::class, VisitorImpl::class);
        Sanctum::ignoreMigrations();
        Telescope::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!config('fixtures.install_done')) {
            return;
        }
        Advertisement::observe(AdvertisementObserver::class);
        Challenge::observe(ChallengeObserver::class);
        Clip::observe(ClipObserver::class);
        NotificationTemplate::observe(NotificationTemplateObserver::class);
        Promotion::observe(PromotionObserver::class);
        Song::observe(SongObserver::class);
        Sticker::observe(StickerObserver::class);
        StickerSection::observe(StickerSectionObserver::class);
        User::observe(UserObserver::class);
        Verification::observe(VerificationObserver::class);
        config([
            'cache.default' => setting('cache_default', config('cache.default')),
            'firebase.credentials.file' => setting('firebase_credentials', config('firebase.credentials.file')),
            'firebase.dynamic_links.default_domain' => setting('firebase_dynamic_links_domain', config('services.firebase.dynamic_links_domain')),
            'fixtures.api_key' => setting('api_key', config('fixtures.api_key')),
            'fixtures.cdn_url' => setting('cdn_url', config('fixtures.cdn_url')),
            'fixtures.gifts_enabled' => setting('gifts_enabled', config('fixtures.gifts_enabled')),
            'fixtures.payment_currency' => setting('payment_currency', config('fixtures.payment_currency')),
            'fixtures.payment_gateway' => setting('payment_gateway', config('fixtures.payment_gateway')),
            'fixtures.ranking_algorithm' => setting('ranking_algorithm', config('fixtures.ranking_algorithm')),
            'queue.default' => setting('queue_default', config('queue.default')),
            'services.bitpay.token' => setting('bitpay_token', config('services.bitpay.token')),
            'services.google_play.credentials' => setting('play_console_credentials', config('services.google_play.credentials')),
            'services.google_play.package_name' => setting('play_store_package_name', config('services.google_play.package_name')),
            'services.instamojo.client_id' => setting('instamojo_client_id', config('services.instamojo.client_id')),
            'services.instamojo.client_secret' => setting('instamojo_client_secret', config('services.instamojo.client_secret')),
            'services.paypal.client_id' => setting('paypal_client_id', config('services.paypal.client_id')),
            'services.paypal.client_secret' => setting('paypal_client_secret', config('services.paypal.client_secret')),
            'services.razorpay.key_id' => setting('razorpay_key_id', config('services.razorpay.key_id')),
            'services.razorpay.key_secret' => setting('razorpay_key_secret', config('services.razorpay.key_secret')),
            'services.sightengine.api_user' => setting('sightengine_api_user', config('services.sightengine.api_user')),
            'services.sightengine.api_secret' => setting('sightengine_api_secret', config('services.sightengine.api_secret')),
            'services.sightengine.continuous' => setting('sightengine_continuous', config('services.sightengine.continuous')),
            'services.stripe.publishable_key' => setting('stripe_publishable_key', config('services.stripe.publishable_key')),
            'services.stripe.secret_key' => setting('stripe_secret_key', config('services.stripe.secret_key')),
        ]);
        switch (setting('filesystems_cloud', config('filesystems.cloud'))) {
            case 'public':
                config(['filesystems.cloud' => 'public']);
                break;
            case 's3':
                config([
                    'filesystems.cloud' => 's3',
                    'filesystems.disks.s3.key' => setting('aws_access_key_id', config('filesystems.disks.s3.key')),
                    'filesystems.disks.s3.secret' => setting('aws_secret_access_key', config('filesystems.disks.s3.secret')),
                    'filesystems.disks.s3.region' => setting('aws_default_region', config('filesystems.disks.s3.region')),
                    'filesystems.disks.s3.bucket' => setting('aws_bucket', config('filesystems.disks.s3.bucket')),
                    'filesystems.disks.s3.endpoint' => setting('aws_endpoint', config('filesystems.disks.s3.endpoint')),
                ]);
                break;
            case 'gcs':
                config([
                    'filesystems.cloud' => 'gcs',
                    'filesystems.disks.gcs.project_id' => setting('gcs_project_id', config('filesystems.disks.gcs.project_id')),
                    'filesystems.disks.gcs.key_file' => setting('gcs_key_file', config('filesystems.disks.gcs.key_file')),
                    'filesystems.disks.gcs.bucket' => setting('gcs_bucket', config('filesystems.disks.gcs.bucket')),
                ]);
                break;
            default:
                break;
        }
        config([
            'mail.from' => [
                'name' => setting('mail_from_name', config('mail.from.name')),
                'address' => setting('mail_from_address', config('mail.from.address')),
            ],
        ]);
        switch (setting('mail_driver', config('mail.driver'))) {
            case 'mailgun':
                config([
                    'mail.driver' => 'mailgun',
                    'services.mailgun.domain' => setting('mailgun_domain', config('services.mailgun.domain')),
                    'services.mailgun.secret' => setting('mailgun_secret', config('services.mailgun.secret')),
                ]);
                break;
            case 'sendmail':
                config(['mail.driver' => 'sendmail']);
                break;
            case 'smtp':
                config([
                    'mail.driver' => 'smtp',
                    'mail.host' => setting('mail_host', config('mail.host')),
                    'mail.port' => setting('mail_port', config('mail.port')),
                    'mail.username' => setting('mail_username', config('mail.username')),
                    'mail.password' => setting('mail_password', config('mail.password')),
                    'mail.encryption' => setting('mail_encryption', config('mail.encryption')),
                ]);
                break;
            default:
                break;
        }
        if (setting('otp_service', config('fixtures.otp_service')) === 'msg91') {
            config([
                'msg91.key' => setting('msg91_key', config('msg91.key')),
            ]);
        }
        if (setting('stripe', config('fixtures.payment_gateway') === 'stripe')) {
            Stripe::setApiKey(setting('stripe_secret_key', config('services.stripe.secret_key')));
        }
    }
}
