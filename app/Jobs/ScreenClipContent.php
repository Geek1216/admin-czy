<?php

namespace App\Jobs;

use App\Clip;
use Aws\Rekognition\RekognitionClient;
use Google\Cloud\VideoIntelligence\V1\Feature;
use Google\Cloud\VideoIntelligence\V1\Likelihood;
use Google\Cloud\VideoIntelligence\V1\VideoAnnotationResults;
use Google\Cloud\VideoIntelligence\V1\VideoIntelligenceServiceClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Sightengine\SightengineClient;

class ScreenClipContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $clip;

    public function __construct(Clip $clip)
    {
        $this->clip = $clip;
    }

    public function handle()
    {
        switch (setting('screening_service', config('fixtures.screening_service'))) {
            case 'aws':
                $porn = $this->checkWithAws();
                break;
            case 'gcp':
                $porn = $this->checkWithGoogleCloud();
                break;
            case 'sightengine':
                $porn = $this->checkWithSightEngine(
                    setting('sightengine_continuous', config('services.sightengine.continuous')));
                break;
            default:
                $porn = false;
        }

        if (!$porn) {
            $this->clip->approved = true;
            $this->clip->save();
        }
    }

    private function checkWithAws() : bool
    {
        /** @var RekognitionClient $client */
        $client = app(RekognitionClient::class);
        $result1 = $client->startContentModeration([
            'Video' => [
                'S3Object' => [
                    'Bucket' => setting('aws_bucket', config('filesystems.disks.s3.bucket')),
                    'Name' => $this->clip->video,
                ],
            ],
        ]);
        $score = [
            'y' => 0,
            'n' => 0,
        ];
        while (true) {
            usleep(1000 * 1000); // poll every 1000ms
            $result2 = $client->getContentModeration([
                'JobId' => $result1['JobId'],
            ]);
            if ($result2['JobStatus'] === 'SUCCEEDED') {
                foreach ($result2['ModerationLabels'] as $label) {
                    if ($label['ModerationLabel']['Confidence'] >= 90) {
                        $score['y']++;
                    } else {
                        $score['n']++;
                    }
                }
                break;
            } else if ($result2['JobStatus'] === 'FAILED') {
                break;
            }
        }

        return $score['y'] > 0 && $score['y'] >= $score['n'];
    }

    private function checkWithGoogleCloud() : bool
    {
        /** @var VideoIntelligenceServiceClient $client */
        $client = app(VideoIntelligenceServiceClient::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $operation = $client->annotateVideo([
            'inputUri' => sprintf(
                'gs://%s/%s',
                setting('gcs_bucket', config('filesystems.disks.gcs.bucket')),
                $this->clip->video
            ),
            'features' => [Feature::EXPLICIT_CONTENT_DETECTION],
        ]);
        $score = [
            'y' => 0,
            'n' => 0,
        ];
        /** @noinspection PhpUnhandledExceptionInspection */
        $operation->pollUntilComplete();
        if ($operation->operationSucceeded()) {
            /** @var VideoAnnotationResults $results */
            $results = $operation->getResult()->getAnnotationResults()[0];
            $explicit = $results->getExplicitAnnotation();
            foreach ($explicit->getFrames() as $frame) {
                if ($frame->getPornographyLikelihood() >= Likelihood::VERY_LIKELY) {
                    $score['y']++;
                } else {
                    $score['n']++;
                }
            }
        }

        return $score['y'] > 0 && $score['y'] >= $score['n'];
    }

    private function checkWithSightEngine(bool $continuous) : bool
    {
        /** @var SightengineClient $client */
        $client = app(SightengineClient::class);
        if ($continuous) {
            $callback = URL::signedRoute('webhooks.sightengine', ['clip' => $this->clip]);
            $output = $client->check(['nudity'])
                ->video(Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($this->clip->video), $callback);
        } else {
            $output = $client->check(['nudity'])
                ->video_sync(Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($this->clip->video));
        }

        if ($output->status === 'success' && !$continuous) {
            $score = [
                'y' => 0,
                'n' => 0,
            ];
            foreach ($output->data->frames as $frame) {
                if ($frame->nudity->raw >= max($frame->nudity->partial, $frame->nudity->safe)) {
                    $score['y']++;
                } else {
                    $score['n']++;
                }
            }

            return $score['y'] > 0 && $score['y'] >= $score['n'];
        }

        return true;
    }
}
