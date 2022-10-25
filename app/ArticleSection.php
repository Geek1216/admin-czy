<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class ArticleSection extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'google_news_topic', 'google_news_language', 'order',
    ];

    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_section_articles',
            'section_id',
            'article_id'
        );
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Article section "%s" was %s.', Str::lower($this->name), $event);
    }
}
