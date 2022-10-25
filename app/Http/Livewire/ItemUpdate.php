<?php

namespace App\Http\Livewire;

use App\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemUpdate extends Component
{
    use WithFileUploads;

    public $item;

    public $name;

    public $image;

    public $price;

    public $value;

    public $minimum;

    public function mount(Item $item)
    {
        $this->item = $item;
        $this->fill($item);
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.item-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.item.image'),
                'dimensions:min_width=128,max_width:1024,ratio=1/1',
            ],
            'price' => ['required', 'integer', 'min:1'],
            'value' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'minimum' => ['required', 'integer', 'min:1'],
        ]);
        if (empty($data['image'])) {
            unset($data['image']);
        } else {
            /** @var UploadedFile $image */
            $image = $data['image'];
            $name = Str::random(15) . '.' . $image->guessExtension();
            $data['image'] = $image->storePubliclyAs('items/images', $name, config('filesystems.cloud'));
            $old_image = $this->item->image;
        }
        $this->item->fill($data);
        $this->item->save();
        if (isset($old_image)) {
            Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->delete($old_image);
        }
        flash()->info(__('Item :name has been updated.', ['name' => $this->item->name]));
        $this->redirect(route('items.show', $this->item));
    }
}
