<?php

namespace App\Http\Livewire;

use App\Promotion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PromotionUpdate extends Component
{
    use WithFileUploads;

    public $promotion;

    public $title;

    public $description;

    public $image;

    public $sticky;

    public function mount(Promotion $promotion)
    {
        $this->promotion = $promotion;
        $this->fill($promotion);
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.promotion-update');
    }

    public function update()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1024'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.promotion.image'),
                'dimensions:min_width=512,max_width:1920',
            ],
            'sticky' => ['nullable', 'boolean'],
        ]);
        if (empty($data['image'])) {
            unset($data['image']);
        } else {
            /** @var UploadedFile $image */
            $image = $data['image'];
            $name = Str::random(15) . '.' . $image->guessExtension();
            $data['image'] = $image->storePubliclyAs('promotions/images', $name, setting('filesystems_cloud', config('filesystems.cloud')));
            $old_image = $this->promotion->image;
        }
        $data['sticky'] = !empty($data['sticky']);
        $this->promotion->fill($data);
        $this->promotion->save();
        if (isset($old_image)) {
            Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->delete($old_image);
        }
        flash()->info(__('Promotion :title has been updated.', ['title' => $this->promotion->title]));
        $this->redirect(route('promotions.show', $this->promotion));
    }
}
