<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClipSection as ClipSectionResource;
use App\ClipSection;
use Illuminate\Http\Request;

class ClipSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = ClipSection::query();
        $q = $request->get('q');
        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        $sections = $query->orderBy('order')->paginate();
        return ClipSectionResource::collection($sections);
    }

    public function show(ClipSection $section)
    {
        return ClipSectionResource::make($section);
    }
}
