<?php

namespace App\Jobs;

use App\Clip;
use App\ClipSection;
use App\Story;
use App\StorySection;
use App\Comment;
use App\Notifications\MentionedYouInComment;
use App\Notifications\TaggedYouInClip;
use App\Notifications\TaggedYouInStory;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Tags\HasTags;

class FindMentionsHashtags implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $content;

    /**
     * @var HasTags
     */
    private $model;

    private $update;

    public function __construct(Model $model, ?string $content = null, bool $update = false)
    {
        $this->model = $model;
        $this->content = $content;
        $this->update = $update;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mentions = [];
        $hashtags1 = [];
        $hashtags2 = [];
        if ($this->content) {
            preg_replace_callback_array(
                [
                    '/@(\w[\w.]+\w)/' => function (array $matches) use (&$mentions) {
                        $mentions[] = $matches[1];
                        return '';
                    },
                    '/#(\w+)/' => function (array $matches) use (&$hashtags1, &$hashtags2) {
                        $hashtags1[] = $matches[0];
                        $hashtags2[] = $matches[1];
                        return '';
                    },
                ],
                $this->content
            );
        }

        $mentions = array_unique($mentions);
        $users = User::query()->whereIn('username', $mentions)->get();
        $this->model->syncTagsWithType($users->pluck('id'), 'mentions');
        if ($users->isNotEmpty() && !$this->update) {
            foreach ($users as $user) {
                if ($this->model instanceof Clip) {
                    $user->notify(new TaggedYouInClip($this->model->user, $this->model));
                    dispatch(new SendNotification(
                        __('notifications.tagged_you_in_clip.title', ['user' => $this->model->user->username]),
                        __('notifications.tagged_you_in_clip.body'),
                        ['clip' => $this->model->id],
                        $user,
                        $this->model
                    ));
                } else if ($this->model instanceof Story) {
                    $user->notify(new TaggedYouInStory($this->model->user, $this->model));
                    dispatch(new SendNotification(
                        __('notifications.tagged_you_in_story.title', ['user' => $this->model->user->username]),
                        __('notifications.tagged_you_in_story.body'),
                        ['story' => $this->model->id],
                        $user,
                        $this->model
                    ));
                } else if ($this->model instanceof Comment) {
                    $user->notify(new MentionedYouInComment($this->model->commentator, $this->model->commentable, $this->model));
                    dispatch(new SendNotification(
                        __('notifications.mentioned_you_in_comment.title', ['user' => $this->model->commentator->username]),
                        __('notifications.mentioned_you_in_comment.body'),
                        ['clip' => $this->model->commentable->id],
                        $user,
                        $this->model->commentable
                    ));
                }
            }
        }

        $hashtags2 = array_unique($hashtags2);
        $this->model->syncTagsWithType($hashtags2, 'hashtags');
        if ($this->model instanceof Clip) {
            $existing = $this->model->sections()->pluck('id');
            $updates = ClipSection::query()
                ->whereIn('name', $hashtags1)
                ->pluck('id');
            $sections = $existing->merge($updates)->unique();
            $this->model->sections()->sync($sections);
        }
        if ($this->model instanceof Story) {
            $existing = $this->model->sections()->pluck('id');
            $updates = StorySection::query()
                ->whereIn('name', $hashtags1)
                ->pluck('id');
            $sections = $existing->merge($updates)->unique();
            $this->model->sections()->sync($sections);
        }
    }
}
