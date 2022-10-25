<?php

namespace App\Support;

use CyrildeWit\EloquentViewable\Contracts\Visitor as Base;
use Illuminate\Support\Str;

class Visitor implements Base
{
    public function id(): string
    {
        return Str::uuid()->toString();
    }

    public function ip(): string
    {
        return '::1';
    }

    public function hasDoNotTrackHeader(): bool
    {
        return false;
    }

    public function isCrawler(): bool
    {
        return false;
    }
}
