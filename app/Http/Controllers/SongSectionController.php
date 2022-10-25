<?php

namespace App\Http\Controllers;

use App\Http\Resources\SongSection as SongSectionResource;
use App\SongSection;
use Illuminate\Http\Request;

class SongSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = SongSection::query();
        $q = $request->get('q');
        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        $sections = $query->withCount('songs')->orderBy('order')->paginate();
        return SongSectionResource::collection($sections);
    }

    public function show(SongSection $section)
    {
        return SongSectionResource::make($section);
    }
}
