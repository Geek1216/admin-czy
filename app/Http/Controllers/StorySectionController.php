<?php

namespace App\Http\Controllers;

use App\Http\Resources\StorySection as StorySectionResource;
use App\StorySection;
use Illuminate\Http\Request;

class StorySectionController extends Controller
{
    public function index(Request $request)
    {
        $query = StorySection::query();
        $q = $request->get('q');
        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        $sections = $query->orderBy('order')->paginate();
        return StorySectionResource::collection($sections);
    }

    public function show(StorySection $section)
    {
        return StorySectionResource::make($section);
    }
}
