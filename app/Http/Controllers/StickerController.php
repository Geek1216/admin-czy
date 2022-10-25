<?php

namespace App\Http\Controllers;

use App\Sticker;
use App\Http\Resources\Sticker as StickerResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StickerController extends Controller
{
    public function index(Request $request)
    {
        $query = Sticker::query();
        $sections = $request->get('sections');
        if ($sections) {
            $query->whereHas('section', function (Builder $query) use ($sections) {
                return $query->whereIn('id', $sections);
            });
        }
        $songs = $query->latest()->paginate();
        return StickerResource::collection($songs);
    }

    public function show(Sticker $song)
    {
        return StickerResource::make($song);
    }
}
