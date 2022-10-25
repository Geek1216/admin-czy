<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Comment;
use CyrildeWit\EloquentViewable\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Overtrue\LaravelFavorite\Favorite;
use Overtrue\LaravelLike\Like;

class ClipsTopCharts extends Component
{
    public $mode = '1D';

    public function render()
    {
        $range = get_range($this->mode);
        $comment = Comment::query()
            ->select(['commentable_id', 'commentable_type'])
            ->addSelect(DB::raw('COUNT(commentable_id) AS comments_count'))
            ->whereHasMorph('commentable', Clip::class)
            ->whereBetween('created_at', $range[0])
            ->groupBy('commentable_type', 'commentable_id')
            ->orderByDesc('comments_count')
            ->first();
        $commented = $comment ? $comment->commentable : null;
        $like = Like::query()
            ->select(['likeable_id', 'likeable_type'])
            ->addSelect(DB::raw('COUNT(likeable_id) AS likes_count'))
            ->whereHasMorph('likeable', Clip::class)
            ->whereBetween('created_at', $range[0])
            ->groupBy('likeable_type', 'likeable_id')
            ->orderByDesc('likes_count')
            ->first();
        $liked = $like ? $like->likeable : null;
        $save = Favorite::query()
            ->select(['favoriteable_id', 'favoriteable_type'])
            ->addSelect(DB::raw('COUNT(favoriteable_id) AS saves_count'))
            ->whereHasMorph('favoriteable', Clip::class)
            ->whereBetween('created_at', $range[0])
            ->groupBy('favoriteable_type', 'favoriteable_id')
            ->orderByDesc('saves_count')
            ->first();
        $saved = $save ? $save->favoriteable : null;
        $view = View::query()
            ->select(['viewable_id', 'viewable_type'])
            ->addSelect(DB::raw('COUNT(viewable_id) AS views_count'))
            ->whereHasMorph('viewable', Clip::class)
            ->whereBetween('viewed_at', $range[0])
            ->groupBy('viewable_type', 'viewable_id')
            ->orderByDesc('views_count')
            ->first();
        $viewed = $view ? $view->viewable : null;
        $data = compact('commented', 'liked', 'saved', 'viewed');
        return view('livewire.clips-top-charts', ['results' => $data]);
    }

    public function update($mode)
    {
        $this->mode = $mode;
    }
}
