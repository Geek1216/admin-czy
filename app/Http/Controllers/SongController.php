<?php

namespace App\Http\Controllers;

use App\Song;
use App\Http\Resources\Song as SongResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::query();
        $q = $request->get('q');
        if ($q) {
            $query->where(function (Builder $query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('artist', 'like', "%$q%")
                    ->orWhere('album', 'like', "%$q%");
            });
        }
        $sections = $request->get('sections');
        if ($sections) {
            $query->whereHas('sections', function (Builder $query) use ($sections) {
                return $query->whereIn('id', $sections);
            });
        }
        $songs = $query->latest()->paginate();
        return SongResource::collection($songs);
    }

    public function show(Song $song)
    {
        return SongResource::make($song);
    }
}
