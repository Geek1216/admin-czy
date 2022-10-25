<?php

namespace App\Http\Livewire;

use App\Challenge;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChallengeUpdate extends Component
{
    use WithFileUploads;

    public $challenge;

    public $hashtag;

    public $image;

    public $description;

    public function mount(Challenge $challenge)
    {
        $this->challenge = $challenge;
        $this->fill($challenge);
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.challenge-update');
    }

    public function update()
    {
        $data = $this->validate([
            'hashtag' => ['required', 'string', 'regex:/^\w+$/', 'max:50'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.challenge.image'),
                'dimensions:min_width=256,max_width:1920,ratio=16/9',
            ],
            'description' => ['nullable', 'string', 'max:1024'],
        ]);
        if (empty($data['image'])) {
            unset($data['image']);
        } else {
            /** @var UploadedFile $image */
            $image = $data['image'];
            $name = Str::random(15) . '.' . $image->guessExtension();
            $data['image'] = $image->storePubliclyAs('challenges/images', $name, setting('filesystems_cloud', config('filesystems.cloud')));
            $old_image = $this->challenge->image;
        }
        $this->challenge->fill($data);
        $this->challenge->save();
        if (isset($old_image)) {
            Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->delete($old_image);
        }
        flash()->info(__('Challenge :hashtag has been updated.', ['hashtag' => $this->challenge->hashtag]));
        $this->redirect(route('challenges.show', $this->challenge));
    }
}
