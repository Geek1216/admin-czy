<?php

namespace App\Http\Livewire;

use App\Song;
use App\SongSection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class SongUpdate extends Component
{
    use WithFileUploads;

    public $song;

    public $sections = [];

    public $audio;

    public $title;

    public $artist;

    public $album;

    public $cover;

    public $duration;

    public $details;

    public function mount(Song $song)
    {
        $this->song = $song;
        $this->fill($song);
        $this->audio = null;
        $this->cover = null;
        $this->sections = $song->sections()->pluck('id')->toArray();
    }

    public function render()
    {
        $song_sections = SongSection::orderBy('name')->get();
        return view('livewire.song-update', compact('song_sections'));
    }

    public function update()
    {
        $data = $this->validate([
            'sections' => ['nullable', 'array'],
            'sections.*' => ['required', 'integer', 'exists:song_sections,id'],
            'audio' => [
                'nullable',
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
        if (empty($data['audio'])) {
            unset($data['audio']);
        } else {
            /** @var UploadedFile $audio */
            $audio = $data['audio'];
            $name = Str::random(15) . '.' . $audio->guessExtension();
            $data['audio'] = $audio->storePubliclyAs('songs/audios', $name, setting('filesystems_cloud', config('filesystems.cloud')));
            $old_audio = $this->song->audio;
        }
        if (empty($data['cover'])) {
            unset($data['cover']);
        } else {
            /** @var UploadedFile $cover */
            $cover = $data['cover'];
            $name = Str::random(15) . '.' . $cover->guessExtension();
            $data['cover'] = $cover->storePubliclyAs('songs/covers', $name, setting('filesystems_cloud', config('filesystems.cloud')));
            $old_cover = $this->song->cover;
        }
        $this->song->fill($data);
        $this->song->sections()->sync((array)($data['sections'] ?? null));
        $this->song->save();
        if (isset($old_audio)) {
            Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->delete($old_audio);
        }
        if (isset($old_cover)) {
            Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->delete($old_cover);
        }
        flash()->info(__('Song :title has been updated.', ['title' => $this->song->title]));
        $this->redirect(route('songs.show', $this->song));
    }
}
