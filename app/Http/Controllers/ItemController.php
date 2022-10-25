<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Resources\Item as ItemResource;

class ItemController extends Controller
{
    public function index()
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $items = Item::query()->latest()->paginate(999);
        return ItemResource::collection($items);
    }
}
