<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\Article as ArticleResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();
        $q = $request->get('q');
        if ($q) {
            $query->where(function (Builder $query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('snippet', 'like', "%$q%")
                    ->orWhere('source', 'like', "%$q%");
            });
        }
        $sections = $request->get('sections');
        if ($sections) {
            $query->whereHas('sections', function (Builder $query) use ($sections) {
                return $query->whereIn('id', $sections);
            });
        }
        $articles = $query->latest('published_at')->paginate($request->get('count', 15));
        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        return ArticleResource::make($article);
    }
}
