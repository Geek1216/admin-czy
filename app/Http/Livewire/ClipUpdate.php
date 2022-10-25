<?php

namespace App\Http\Livewire;

use App\Clip;
use App\ClipSection;
use App\Jobs\FindMentionsHashtags;
use App\Jobs\SendNotification;
use App\Notifications\ClipApproved;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ClipUpdate extends Component
{
    public $clip;

    public $sections = [];

    public $description;

    public $language;

    public $private;

    public $duet;

    public $comments;

    public $approved;

    public function mount(Clip $clip)
    {
        $this->clip = $clip;
        $this->fill($clip);
        $this->sections = $clip->sections()->pluck('id')->toArray();
    }

    public function render()
    {
        $clip_sections = ClipSection::orderBy('name')->get();
        return view('livewire.clip-update', compact('clip_sections'));
    }

    public function update()
    {
        $data = $this->validate([
            'sections' => ['nullable', 'array'],
            'sections.*' => ['required', 'integer', 'exists:clip_sections,id'],
            'description' => ['nullable', 'string', 'max:300'],
            'language' => ['required', 'string', Rule::in(array_keys(config('fixtures.languages')))],
            'private' => ['nullable', 'boolean'],
            'duet' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'boolean'],
            'approved' => ['nullable', 'boolean'],
        ]);
        $data['private'] = $data['private'] ?? false;
        $data['comments'] = $data['comments'] ?? false;
        $data['approved'] = $data['approved'] ?? false;
        $this->clip->fill($data);
        $retag = $this->clip->isDirty('description');
        $approved = $this->clip->isDirty('approved') && $this->clip->approved;
        $this->clip->save();
        $this->clip->sections()->sync((array)($data['sections'] ?? null));
        if ($retag) {
            dispatch(new FindMentionsHashtags($this->clip, $this->clip->description));
        }

        if ($approved) {
            $this->clip->user->notify(new ClipApproved($this->clip));
            dispatch(new SendNotification(
                __('notifications.clip_approved.title'),
                __('notifications.clip_approved.body'),
                ['clip' => $this->clip->id],
                $this->clip->user,
                $this->clip,
                false
            ));
        }

        flash()->info(__('Clip #:id has been updated.', ['id' => $this->clip->id]));
        $this->redirect(route('clips.show', $this->clip));
    }
}
