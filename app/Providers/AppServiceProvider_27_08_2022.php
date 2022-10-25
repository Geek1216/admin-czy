<?php

namespace App\Providers;

use App\Advertisement;
use App\Challenge;
use App\Clip;
use App\Story;
use App\NotificationTemplate;
use App\Observers\AdvertisementObserver;
use App\Observers\ChallengeObserver;
use App\Observers\ClipObserver;
use App\Observers\StoryObserver;
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
use CyrildeWit\EloquentViewable\Contracts\Visitor as VisitorContract;
use Facebook\Facebook;
use Google\Cloud\VideoIntelligence\V1\VideoIntelligenceServiceClient;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Laravel\Telescope\Telescope;
use Sightengine\SightengineClient;
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
        $this->app->bind(RekognitionClient::class, function () {
            return new RekognitionClient([
                'credentials' => [
                    'key' => config('filesystems.disks.s3.key'),
                    'secret' => config('filesystems.disks.s3.secret'),
                ],
                'region' => config('filesystems.disks.s3.region'),
                'version' => 'latest',
            ]);
        });
        $this->app->bind(SightengineClient::class, function () {
            return new SightengineClient(
                config('services.sightengine.api_user'),
                config('services.sightengine.api_secret')
            );
        });
        $this->app->bind(Twilio::class, function () {
            return new Twilio(
                config('services.twilio.sid'),
                config('services.twilio.auth_token')
            );
        });
        $this->app->bind('twilio.verify', function () {
            return app(Twilio::class)
                ->verify->v2->services(config('services.twilio.verify_sid'));
        });
        $this->app->bind(VideoIntelligenceServiceClient::class, function () {
            return new VideoIntelligenceServiceClient([
                'credentials' => config('services.google_cloud.credentials'),
            ]);
        });
        $this->app->bind(Facebook::class, function () {
            return new Facebook([
                'app_id' => config('services.facebook.app_id'),
                'app_secret' => config('services.facebook.app_secret'),
                'default_graph_version' => 'v2.10',
            ]);
        });
        $this->app->bind(\Google_Client::class, function () {
            return new \Google_Client(['client_id' => config('services.google.client_id')]);
        });
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
        Advertisement::observe(AdvertisementObserver::class);
        Challenge::observe(ChallengeObserver::class);
        Clip::observe(ClipObserver::class);
        Story::observe(StoryObserver::class);
        NotificationTemplate::observe(NotificationTemplateObserver::class);
        Promotion::observe(PromotionObserver::class);
        Song::observe(SongObserver::class);
        Sticker::observe(StickerObserver::class);
        StickerSection::observe(StickerSectionObserver::class);
        User::observe(UserObserver::class);
        Verification::observe(VerificationObserver::class);
    }
}
