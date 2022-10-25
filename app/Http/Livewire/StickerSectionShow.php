<?php

namespace App\Http\Livewire;

use App\Sticker;
use App\StickerSection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class StickerSectionShow extends Component
{
    use WithFileUploads;

    public $section;

    public $images;

    public function mount(StickerSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        $activities = $this->section->activities()->latest()->paginate();
        return view('livewire.sticker-section-show', compact('activities'));
    }

    public function upload()
    {
        $data = $this->validate([
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.sticker.image'),
                'dimensions:min_width=256,min_height=256,max_width:1024,max_height:1024',
            ],
        ]);
        array_walk($data['images'], function (UploadedFile $image) {
            $name = Str::random(15) . '.' . $image->guessExtension();
            $image = $image->storePubliclyAs('stickers', $name, setting('filesystems_cloud', config('filesystems.cloud')));
            $this->section->stickers()->create(compact('image'));
        });
        flash()->success(__(':count sticker(s) were successfully added.', ['count' => count($data['images'])]));
        $this->redirect(route('sticker-sections.show', $this->section));
    }
}
