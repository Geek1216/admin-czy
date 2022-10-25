<?php

namespace App\Http\Livewire;

use App\ClipSection;
use App\Jobs\UploadClipManually;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClipCreate extends Component
{
    use WithFileUploads;

    public $user;

    public $sections = [];

    public $video;

    public $description;

    public $language = 'hin';

    public $duet = true;

    public $private;

    public $comments = true;

    public function render()
    {
        $clip_sections = ClipSection::query()
            ->where('name', 'not like', '#%')
            ->orderBy('name')
            ->get();
        return view('livewire.clip-create', compact('clip_sections'));
    }

    public function create()
    {
        $data = $this->validate([
            'user' => ['required', 'numeric', 'exists:users,id'],
            'sections' => ['nullable', 'array'],
            'sections.*' => ['required', 'integer', 'exists:clip_sections,id'],
            'video' => [
                'required',
                'file',
                'mimetypes:video/mp4',
                'max:' . config('fixtures.upload_limits.clip.video'),
            ],
            'description' => ['nullable', 'string', 'max:300'],
            'language' => [
                'required',
                'string',
                Rule::in(array_keys(config('fixtures.languages'))),
            ],
            'duet' => ['nullable', 'boolean'],
            'private' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'boolean'],
        ]);
        $data['private'] = $data['private'] ?? false;
        $data['comments'] = $data['comments'] ?? false;
        /** @var UploadedFile $video */
        $video = $data['video'];
        $data['video'] = $video->storeAs('temp', Str::random(32) . '.mp4');
        dispatch(new UploadClipManually($data));
        flash()->success(__('The uploaded clip will be processed shortly.'));
        $this->redirect(route('clips.index'));
    }
}
