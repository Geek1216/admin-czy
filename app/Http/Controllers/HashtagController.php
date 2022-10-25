<?php

namespace App\Http\Controllers;

use App\Http\Resources\Hashtag as HashtagResource;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class HashtagController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q'));
        if ($q && ($q = ltrim($q, '#'))) {
            $query = Tag::query()
                ->containing($q)
                ->withType('hashtags')
                ->orderBy('name');
        } else {
            $query = Tag::query()
                ->withType('hashtags')
                ->latest();
        }

        $hashtags = $query->paginate(15);
        return HashtagResource::collection($hashtags);
    }
}
