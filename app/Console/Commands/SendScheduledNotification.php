<?php

namespace App\Console\Commands;

use App\Clip;
use App\Story;
use App\Jobs\SendNotification;
use App\NotificationSchedule;
use App\NotificationTemplate;
use Illuminate\Console\Command;

class SendScheduledNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications as set by admin.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hhmmss = now()->format('H:i:00');
        /** @var NotificationSchedule|null $schedule */
        NotificationSchedule::where('time', $hhmmss)
            ->each(function (NotificationSchedule $schedule) {
                if ($schedule->template) {
                    $template = $schedule->template;
                } else {
                    $template = NotificationTemplate::inRandomOrder()->first();
                }

                if ($template) {
                    $title = $template->title;
                    if ($schedule->clips === 'latest') {
                        $clip = Clip::latest()->first();
                    } else if ($schedule->clips === 'random') {
                        $clip = Clip::select('id')->inRandomOrder()->first();
                        if ($clip) {
                            $clip = Clip::findOrFail($clip->id);
                        }
                    }
                    if ($schedule->stories === 'latest') {
                        $story = Story::latest()->first();
                    } else if ($schedule->clips === 'random') {
                        $story = Story::select('id')->inRandomOrder()->first();
                        if ($story) {
                            $story = Story::findOrFail($story->id);
                        }
                    }
                    if (isset($clip) && $clip->description) {
                        $title = str_replace("\n", ' ', $clip->description);
                    }
                    if (isset($story) && $story->description) {
                        $title = str_replace("\n", ' ', $story->description);
                    }

                    $data = null;
                    if (isset($clip)) {
                        $data['clip'] = $clip->id;
                    }

                    if (isset($story)) {
                        $data['story'] = $story->id;
                    }
                    dispatch(new SendNotification($title, $body = $template->body, $data, null, $clip ?? null, $story ?? null));
                    activity()
                        ->performedOn($schedule)
                        ->log($schedule->getDescriptionForEvent('sent'));
                }
            });
    }
}
