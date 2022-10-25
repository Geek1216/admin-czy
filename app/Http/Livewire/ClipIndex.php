<?php

namespace App\Http\Livewire;

use App\Clip;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\DynamicLink\AndroidInfo;
use Kreait\Firebase\DynamicLink\CreateDynamicLink;
use Kreait\Firebase\DynamicLink\SocialMetaTagInfo;
use Kreait\Firebase\DynamicLinks;
use Livewire\Component;
use Livewire\WithPagination;

class ClipIndex extends Component
{
    use WithPagination;

    public $boost = false;
    /** @var Clip|null */
    public $boostable = null;
    public $boostCount = 100;
    public $boostType = null;

    public $filtering = false;

    public $language;

    public $length = '10';

    public $links = [];

    public $order = ['created_at' => 'desc'];

    public $search;

    public $section;

    public function attach(int $clip, int $section)
    {
        /** @var Clip $clip */
        $clip = Clip::query()->findOrFail($clip);
        $clip->sections()->attach($section);
    }

    public function detach(int $clip, int $section)
    {
        /** @var Clip $clip */
        $clip = Clip::query()->findOrFail($clip);
        $clip->sections()->detach($section);
    }

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Clip::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('description', 'like', "%$this->search%")
                    ->orWhereHas('user', function (Builder $query) {
                        $query->where('name', 'like', "%$this->search%")
                            ->orWhere('email', 'like', "%$this->search%");
                    });
            });
        }

        if ($this->section) {
            $query->whereHas('sections', function (Builder $query) {
                $query->whereKey($this->section);
            });
        }

        if ($this->language) {
            $query->where('language', $this->language);
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $clips = $query->paginate($this->length);
        return view('livewire.clip-index', compact('clips'));
    }

    public function shortlink(int $clip)
    {
        /** @var Clip $clip */
        $clip = Clip::query()->findOrFail($clip);
        $cdn = setting('cdn_url', config('fixtures.cdn_url'));
        $screenshot = $cdn
            ? $cdn.$clip->screenshot
            : Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))
                ->url($clip->screenshot);
        $url = route('links.share', ['resource' => 'clips', 'first' => $clip->id]);
        /** @var DynamicLinks $links */
        $links = app('firebase.dynamic_links');
        $link = CreateDynamicLink::forUrl($url)
            ->withAndroidInfo(AndroidInfo::new()
                ->withPackageName($package = setting('firebase_package_name', config('services.firebase.package_name'))))
            ->withDynamicLinkDomain(setting('firebase_dynamic_links_domain', config('services.firebase.dynamic_links_domain')))
            ->withSocialMetaTagInfo(
                SocialMetaTagInfo::new()
                    ->withTitle($clip->description_short ?: sprintf('%s\'s clip', $clip->user->username))
                    ->withDescription(sprintf('Hey there! Check our this cool clip and for more, download this app https://play.google.com/store/apps/details?id=%s from Play Store.', $package))
                    ->withImageLink($screenshot));
        $link = $links->createShortLink($link);
        $this->links[$clip->id] = (string) $link;
    }

    /**
     * @param string $column
     * @param string|false $direction
     */
    public function sort(string $column, $direction)
    {
        if ($direction) {
            $this->order[$column] = $direction;
        } else {
            unset($this->order[$column]);
        }

        $this->resetPage();
    }

    public function updatingLength()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSection()
    {
        $this->resetPage();
    }

    public function updatingLanguage()
    {
        $this->resetPage();
    }

    public function hideBoostDialog()
    {
        $this->boost = false;
        $this->boostable = $this->boostType = null;
        $this->boostCount = 100;
    }

    public function showBoostDialog(int $id, string $type)
    {
        $this->boost = true;
        $this->boostable = Clip::query()->findOrFail($id);
        $this->boostCount = 100;
        $this->boostType = $type;
    }

    public function submitBoost()
    {
        $this->validate([
            'boostCount' => ['required', 'numeric', 'min:100', 'max:99999'],
        ]);
        if ($this->boostType === 'views') {
            for ($i = 1; $i <= $this->boostCount; $i++) {
                views($this->boostable)->record();
            }
            Cache::forget("clip_{$this->boostable->id}_views");
            Cache::forget("user_{$this->boostable->user->id}_views");
        } else {
            User::query()
                ->take($this->boostCount)
                ->inRandomOrder()
                ->each(function (User $user) {
                    switch ($this->boostType) {
                        case 'likes':
                            if (!$this->boostable->isLikedBy($user)) {
                                $user->like($this->boostable);
                            }
                            Cache::forget("clip_{$this->boostable->id}_likes");
                            Cache::forget("user_{$this->boostable->user->id}_likes");
                            break;
                        default:
                            break;
                    }
                });
        }
        flash()->success(__('Successfully boosted :type for selected clip.', ['type' => $this->boostType]));
        $this->redirectRoute('clips.index');
    }
}
