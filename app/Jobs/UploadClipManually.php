<?php

namespace App\Jobs;

use App\Clip;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class UploadClipManually implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * UploadClipManually constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $data['video'] = 'videos/' . Str::random(15) . '.mp4';
        $data['screenshot'] = 'screenshots/' . Str::random(15) . '.png';
        $data['preview'] = 'previews/' . Str::random(15) . '.gif';
        $format = new X264('aac', 'libx264');
        $format->setAdditionalParameters(['-crf', '29', '-movflags', 'faststart']);
        $format->setKiloBitrate(2000);
        $media = FFMpeg::open($this->data['video'])
            ->export()
            ->inFormat($format)
            ->resize(540, 960)
            ->toDisk('local')
            ->save($temp1 = Str::random(15) . '.mp4');
        $data['duration'] = $media->getDurationInSeconds();
        FFMpeg::open($temp1)
            ->export()
            ->frame(TimeCode::fromSeconds(1))
            ->toDisk('local')
            ->save($temp2 = Str::random(15) . '.png');
        $size = config('fixtures.max_preview_size');
        $image = Image::make(Storage::disk('local')->get($temp2))
            ->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('png');
        $ffmpeg = FFMpeg::open($temp1);
        /** @var Video $video */
        $driver = $ffmpeg->getFFMpegDriver();
        $video = $ffmpeg->getDriver()->get();
        $temp3 = storage_path('app/temp/' . Str::random(15) . '.gif');
        $this->createGif($driver, $video->getPathfile(), $temp3, $size);
        Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))
            ->put($data['video'], Storage::disk('local')->get($temp1), 'public');
        Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))
            ->put($data['screenshot'], (string) $image, 'public');
        Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))
            ->put($data['preview'], file_get_contents($temp3), 'public');
        Storage::delete([$this->data['video'], $temp1, $temp2]);
        unlink($temp3);
        /** @var Clip $clip */
        $clip = Clip::make($data);
        $clip->user_id = $data['user'];
        $clip->approved = true;
        $clip->save();
        if ($data['sections'] ?? null) {
            $clip->sections()->attach($data['sections']);
        }

        if ($clip->description) {
            dispatch(new FindMentionsHashtags($clip, $clip->description));
        }
    }

    private function createGif(FFMpegDriver $driver, string $video, string $gif, $size)
    {
        $commands[] = '-ss';
        $commands[] = '1';
        $commands[] = '-t';
        $commands[] = '2';
        $commands[] = '-i';
        $commands[] = $video;
        $commands[] = '-vf';
        $commands[] = "fps=4,scale=iw*min(1\,min($size/iw\,$size/ih)):-1";
        $commands[] = '-gifflags';
        $commands[] = '+transdiff';
        $commands[] = '-y';
        $commands[] = $gif;
        $driver->command($commands);
    }
}
