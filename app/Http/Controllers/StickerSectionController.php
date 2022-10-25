<?php

namespace App\Http\Controllers;

use App\Http\Resources\StickerSection as StickerSectionResource;
use App\StickerSection;
use Illuminate\Http\Request;

class StickerSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = StickerSection::query()
            ->whereHas('stickers');
        $q = $request->get('q');
        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        $sections = $query->withCount('stickers')->orderBy('order')->paginate();
        return StickerSectionResource::collection($sections);
    }

    public function show(StickerSection $section)
    {
        return StickerSectionResource::make($section);
    }
}
