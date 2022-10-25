<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'platform', 'push_service', 'push_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
