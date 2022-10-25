<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Article extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $dates = [
        'published_at',
    ];

    protected $fillable = [
        'title', 'snippet', 'image', 'link', 'source', 'published_at', 'checksum',
    ];

    protected $hidden = [
        'checksum',
    ];

    public function sections()
    {
        return $this->belongsToMany(
            ArticleSection::class,
            'article_section_articles',
            'article_id',
            'section_id'
        );
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Article "%s" was %s.', Str::lower($this->title_short), $event);
    }

    public function getImageShortAttribute()
    {
        if (mb_strlen($this->image) > 20) {
            return mb_substr($this->image, 0, 20) . '…';
        }
        return $this->image;
    }

    public function getLinkShortAttribute()
    {
        if (mb_strlen($this->link) > 20) {
            return mb_substr($this->link, 0, 20) . '…';
        }
        return $this->link;
    }

    public function getTitleShortAttribute()
    {
        if (mb_strlen($this->title) > 20) {
            return mb_substr($this->title, 0, 20) . '…';
        }
        return $this->title;
    }

    public function tapActivity(Activity $activity, string $event)
    {
        if ($properties = $activity->changes()) {
            foreach ($this->hidden as $attr) {
                if (Arr::has($properties, "attributes.$attr")) {
                    $attributes = $properties->get('attributes');
                    $attributes[$attr] = '*hidden*';
                    $properties->put('attributes', $attributes);
                }
                if (Arr::has($properties, "old.$attr")) {
                    $old = $properties->get('old');
                    $old[$attr] = '*hidden*';
                    $properties->put('old', $old);
                }
            }
            $activity->properties = $properties;
        }
    }
}
