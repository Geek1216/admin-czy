<?php

namespace App\Http\Livewire;

use App\Advertisement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdvertisementUpdate extends Component
{
    use WithFileUploads;

    public $advertisement;

    public $location;

    public $type;

    public $network;

    public $unit;

    public $image;

    public $link;

    public $interval;

    public function mount(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
        $this->fill($advertisement);
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.advertisement-update');
    }

    public function update()
    {
        $data = $this->validate([
            'location' => ['required', 'string', Rule::in(array_keys(config('fixtures.advertisement_locations')))],
            'network' => ['required', 'string', Rule::in(array_keys(config('fixtures.advertisement_networks')))],
            'type' => ['required', 'string', Rule::in(array_keys(config('fixtures.advertisement_types')))],
            'unit' => ['nullable', 'required_unless:network,custom', 'string', 'max:255'],
            'image' => [
                'nullable',
                'image',
                'mimes:gif,jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.advertisement.image'),
                'dimensions:min_width=320,max_width:1920',
            ],
            'link' => ['nullable', 'string', 'required_if:network,custom', 'url', 'max:255'],
            'interval' => ['nullable', 'required_unless:type,banner', 'integer', 'min:5', 'max:65535'],
        ]);
        $exists = Advertisement::query()
            ->whereKeyNot($this->advertisement->id)
            ->where('location', $data['location'])
            ->where('type', $data['type'])
            ->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'type' => __('You already have an this ad type for this location.'),
            ]);
        }

        if (!in_array($data['type'], (array) config('fixtures.advertisement_location_to_types.' . $data['location']))) {
            throw ValidationException::withMessages([
                'type' => __('This ad type is not supported for selected location.'),
            ]);
        } else if (!in_array($data['type'], (array) config('fixtures.advertisement_network_to_types.' . $data['network']))) {
            throw ValidationException::withMessages([
                'type' => __('This ad type is not supported for selected network.'),
            ]);
        } else if ($data['network'] === 'custom' && empty($data['image']) && empty($this->advertisement->image)) {
            throw ValidationException::withMessages([
                'image' => __('validation.required', ['attribute' => 'image']),
            ]);
        } else if ($data['network'] === 'custom' && isset($data['image'])) {
            /** @var UploadedFile $image */
            $image = $data['image'];
            $name = Str::random(15) . '.' . $image->guessExtension();
            $data['image'] = $image->storePubliclyAs('advertisements/images', $name, setting('filesystems_cloud', config('filesystems.cloud')));
            $old_image = $this->advertisement->image;
        } else if ($data['network'] !== 'custom') {
            $data['image'] = $data['link'] = null;
        } else {
            unset($data['image']);
        }

        $this->advertisement->fill($data);
        $this->advertisement->save();
        if (isset($old_image)) {
            Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->delete($old_image);
        }

        flash()->info(__('Advertisement #:id has been updated.', ['id' => $this->advertisement->id]));
        $this->redirect(route('advertisements.show', $this->advertisement));
    }

    public function updated($name, $value)
    {
        if ($name === 'location') {
            $valid = in_array($this->type, $allowed = config('fixtures.advertisement_location_to_types.' . $value));
            if (!$valid) {
                $this->type = $allowed[0];
            }
        } else if ($name === 'network') {
            $valid = in_array($this->type, $allowed = config('fixtures.advertisement_network_to_types.' . $value));
            if (!$valid) {
                $this->type = $allowed[0];
            }
        }
    }
}
