<?php

namespace App\Http\Livewire;

use App\Promotion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PromotionCreate extends Component
{
    use WithFileUploads;

    public $title;

    public $description;

    public $image;

    public $sticky;

    public function render()
    {
        return view('livewire.promotion-create');
    }

    public function create()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1024'],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.promotion.image'),
                'dimensions:min_width=512,max_width:1920',
            ],
            'sticky' => ['nullable', 'boolean'],
        ]);
        /** @var UploadedFile $image */
        $image = $data['image'];
        $name = Str::random(15) . '.' . $image->guessExtension();
        $data['image'] = $image->storePubliclyAs('promotions/images', $name, setting('filesystems_cloud', config('filesystems.cloud')));
        $data['sticky'] = !empty($data['sticky']);
        $promotion = Promotion::create($data);
        flash()->success(__('Promotion :title has been successfully added.', ['title' => $promotion->title]));
        $this->redirect(route('promotions.show', $promotion));
    }
}
