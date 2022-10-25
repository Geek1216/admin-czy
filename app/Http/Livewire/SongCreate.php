<?php

namespace App\Http\Livewire;

use App\Song;
use App\SongSection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class SongCreate extends Component
{
    use WithFileUploads;

    public $sections = [];

    public $audio;

    public $title;

    public $artist;

    public $album;

    public $cover;

    public $duration;

    public $details;

    public function mount()
    {
        $this->duration = 15;
    }

    public function render()
    {
        $song_sections = SongSection::orderBy('name')->get();
        return view('livewire.song-create', compact('song_sections'));
    }

    public function create()
    {
        $data = $this->validate([
            'sections' => ['nullable', 'array'],
            'sections.*' => ['required', 'integer', 'exists:song_sections,id'],
            'audio' => [
                'required',
                'file',
                'max:' . config('fixtures.upload_limits.song.audio'),
            ],
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['nullable', 'string', 'max:255'],
            'album' => ['nullable', 'string', 'max:255'],
            'cover' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.song.cover'),
                'dimensions:min_width=256,max_width:1920,ratio=1/1',
            ],
            'duration' => ['required', 'integer', 'min:1', 'max:65535'],
            'details' => ['nullable', 'string', 'url', 'max:255'],
        ]);
        /** @var UploadedFile $audio */
        $audio = $data['audio'];
        $name = Str::random(15) . '.' . $audio->guessExtension();
        $data['audio'] = $audio->storePubliclyAs('songs/audios', $name, setting('filesystems_cloud', config('filesystems.cloud')));
        if (empty($data['cover'])) {
            unset($data['cover']);
        } else {
            /** @var UploadedFile $cover */
            $cover = $data['cover'];
            $name = Str::random(15) . '.' . $cover->guessExtension();
            $data['cover'] = $cover->storePubliclyAs('songs/covers', $name, setting('filesystems_cloud', config('filesystems.cloud')));
        }
        /** @var Song $song */
        $song = Song::create($data);
        $song->sections()->sync((array)($data['sections'] ?? null));
        flash()->success(__('Song :title has been successfully added.', ['title' => $song->title]));
        $this->redirect(route('songs.show', $song));
    }
}
