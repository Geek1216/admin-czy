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

class Group extends Model implements Viewable
{
    use Favoriteable, HasComments, HasTags, InteractsWithViews, Likeable, LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $casts = [
        
    ];

    protected $fillable = [
         'user_id','name', 'unique_name','category','thumbnail', 'link'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'categoryId', 'id');
        // return $this->hasOne(Category::class, 'id')
            // ->select('id', 'category_name');
    }
}
