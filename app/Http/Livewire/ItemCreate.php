<?php

namespace App\Http\Livewire;

use App\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemCreate extends Component
{
    use WithFileUploads;

    public $name;

    public $image;

    public $price = 100;

    public $value = 100;

    public $minimum = 10;

    public function render()
    {
        return view('livewire.item-create');
    }

    public function create()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
              //  'max:' . config('fixtures.upload_limits.item.image'),
                'dimensions:min_width=128,max_width:1024,ratio=1/1',
            ],
            'price' => ['required', 'integer', 'min:1'],
            'value' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'minimum' => ['required', 'integer', 'min:1'],
        ]);
        /** @var UploadedFile $image */
        $image = $data['image'];
        $name = Str::random(15) . '.' . $image->guessExtension();
        $data['image'] = $image->storePubliclyAs('items/images', $name, config('filesystems.cloud'));
        /** @var Item $item */
        $item = Item::create($data);
        flash()->success(__('Item :name has been successfully added.', ['name' => $item->name]));
        $this->redirect(route('items.show', $item));
    }
}
