<?php

namespace App;

use BeyondCode\Comments\Traits\HasComments;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Overtrue\LaravelLike\Traits\Likeable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use SoftDeletes;

class Category extends Model implements Viewable
{
    use Favoriteable, HasComments, HasTags, InteractsWithViews, Likeable, LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $casts = [
        
    ];

    protected $fillable = [
         'category_name'
    ];
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
