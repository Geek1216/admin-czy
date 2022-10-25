<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Http\Resources\Credit as CreditResource;

class CreditController extends Controller
{
    public function index()
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $items = Credit::query()->orderBy('order')->paginate(999);
        return CreditResource::collection($items);
    }
}
