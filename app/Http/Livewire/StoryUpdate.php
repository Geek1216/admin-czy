<?php

namespace App\Http\Livewire;

use App\Story;
use App\StorySection;
use App\Jobs\FindMentionsHashtags;
use App\Jobs\SendNotification;
use App\Notifications\StoryApproved;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StoryUpdate extends Component
{
    public $story;

    public $sections = [];

    public $description;

    public $language;

    public $private;

    public $comments;

    public $approved;

    public function mount(Story $story)
    {
        $this->story = $story;
        $this->fill($story);
        $this->sections = $story->sections()->pluck('id')->toArray();
    }

    public function render()
    {
        $story_sections = StorySection::orderBy('name')->get();
        return view('livewire.story-update', compact('story_sections'));
    }

    public function update()
    {
        $data = $this->validate([
            'sections' => ['nullable', 'array'],
            'sections.*' => ['required', 'integer', 'exists:story_sections,id'],
            'description' => ['nullable', 'string', 'max:300'],
            'language' => ['required', 'string', Rule::in(array_keys(config('fixtures.languages')))],
            'private' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'boolean'],
            'approved' => ['nullable', 'boolean'],
        ]);
        $data['private'] = $data['private'] ?? false;
        $data['comments'] = $data['comments'] ?? false;
        $data['approved'] = $data['approved'] ?? false;
        $this->story->fill($data);
        $retag = $this->story->isDirty('description');
        $approved = $this->story->isDirty('approved') && $this->story->approved;
        $this->story->save();
        $this->story->sections()->sync((array)($data['sections'] ?? null));
        if ($retag) {
            dispatch(new FindMentionsHashtags($this->story, $this->story->description));
        }

        if ($approved) {
            $this->story->user->notify(new StoryApproved($this->story));
            dispatch(new SendNotification(
                __('notifications.story_approved.title'),
                __('notifications.story_approved.body'),
                ['story' => $this->story->id],
                $this->story->user,
                $this->story,
                false
            ));
        }

        session()->flash('info', __('Story #:id has been updated.', ['id' => $this->story->id]));
        return redirect()->route('stories.show', $this->story);
    }
}
