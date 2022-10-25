<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleSection as ArticleSectionResource;
use App\ArticleSection;
use Illuminate\Http\Request;

class ArticleSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = ArticleSection::query();
        $q = $request->get('q');
        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        $sections = $query->withCount('articles')->orderBy('order')->paginate();
        return ArticleSectionResource::collection($sections);
    }

    public function show(ArticleSection $section)
    {
        return ArticleSectionResource::make($section);
    }
}
