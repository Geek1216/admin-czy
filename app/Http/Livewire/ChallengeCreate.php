<?php

namespace App\Http\Livewire;

use App\Challenge;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChallengeCreate extends Component
{
    use WithFileUploads;

    public $hashtag;

    public $image;

    public $description;

    public function render()
    {
        return view('livewire.challenge-create');
    }

    public function create()
    {
        $data = $this->validate([
            'hashtag' => ['required', 'string', 'regex:/^\w+$/', 'max:50'],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.challenge.image'),
                'dimensions:min_width=256,max_width:1920,ratio=16/9',
            ],
            'description' => ['nullable', 'string', 'max:1024'],
        ]);
        /** @var UploadedFile $image */
        $image = $data['image'];
        $name = Str::random(15) . '.' . $image->guessExtension();
        $data['image'] = $image->storePubliclyAs('challenges/images', $name, setting('filesystems_cloud', config('filesystems.cloud')));
        /** @var Challenge $challenge */
        $challenge = Challenge::create($data);
        flash()->success(__('Challenge :hashtag has been successfully added.', ['hashtag' => $challenge->hashtag]));
        $this->redirect(route('challenges.show', $challenge));
    }
}
