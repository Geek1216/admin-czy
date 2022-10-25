<?php

namespace App\Http\Livewire;

use App\Clip;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\DynamicLink\AndroidInfo;
use Kreait\Firebase\DynamicLink\CreateDynamicLink;
use Kreait\Firebase\DynamicLink\SocialMetaTagInfo;
use Kreait\Firebase\DynamicLinks;
use Livewire\Component;

class ClipShow extends Component
{
    public $clip;

    public $link;

    public function mount(Clip $clip)
    {
        $this->clip = $clip;
    }

    public function render()
    {
        $activities = $this->clip->activities()->latest()->paginate();
        return view('livewire.clip-show', compact('activities'));
    }

    public function shortlink()
    {
        $cdn = setting('cdn_url', config('fixtures.cdn_url'));
        $screenshot = $cdn
            ? $cdn.$this->clip->screenshot
            : Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))
                ->url($this->clip->screenshot);
        $url = route('links.share', ['resource' => 'clips', 'first' => $this->clip->id]);
        /** @var DynamicLinks $links */
        $links = app('firebase.dynamic_links');
        $link = CreateDynamicLink::forUrl($url)
            ->withAndroidInfo(AndroidInfo::new()
                ->withPackageName($package = setting('firebase_package_name', config('services.firebase.package_name'))))
            ->withDynamicLinkDomain(setting('firebase_dynamic_links_domain', config('services.firebase.dynamic_links_domain')))
            ->withSocialMetaTagInfo(
                SocialMetaTagInfo::new()
                    ->withTitle($this->clip->description_short ?: sprintf('%s\'s clip', $this->clip->user->username))
                    ->withDescription(sprintf('Hey there! Check our this cool clip and for more, download this app https://play.google.com/store/apps/details?id=%s from Play Store.', $package))
                    ->withImageLink($screenshot));
        $link = $links->createShortLink($link);
        $this->link = (string) $link;
    }
}
