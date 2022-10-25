<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Comment;
use App\User;
use CyrildeWit\EloquentViewable\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Overtrue\LaravelFavorite\Favorite;
use Overtrue\LaravelLike\Like;

class UsersTopCharts extends Component
{
    public $mode = '1D';

    public function render()
    {
        $range = get_range($this->mode);
        $comment = Comment::query()
            ->select('clips.user_id')
            ->addSelect(DB::raw('COUNT(commentable_id) AS comments_count'))
            ->leftJoin('clips', 'commentable_id', '=', 'clips.id')
            ->whereHasMorph('commentable', Clip::class)
            ->whereBetween('comments.created_at', $range[0])
            ->groupBy('clips.user_id')
            ->orderByDesc('comments_count')
            ->first();
        $commented = $comment ? User::query()->find($comment->user_id) : null;
        $follower = DB::table(config('follow.relation_table', 'user_follower'))
            ->select('following_id')
            ->addSelect(DB::raw('COUNT(following_id) AS follower_count'))
            ->whereBetween('created_at', $range[0])
            ->whereNotNull('accepted_at')
            ->groupBy('following_id')
            ->orderByDesc('follower_count')
            ->first();
        $followed = $follower ? User::query()->find($follower->following_id) : $follower;
        $like = Like::query()
            ->select('clips.user_id')
            ->addSelect(DB::raw('COUNT(likeable_id) AS likes_count'))
            ->leftJoin('clips', 'likeable_id', '=', 'clips.id')
            ->whereHasMorph('likeable', Clip::class)
            ->whereBetween('likes.created_at', $range[0])
            ->groupBy('clips.user_id')
            ->orderByDesc('likes_count')
            ->first();
        $liked = $like ? User::query()->find($like->user_id) : null;
        $save = Favorite::query()
            ->select('clips.user_id')
            ->addSelect(DB::raw('COUNT(favoriteable_id) AS favorites_count'))
            ->leftJoin('clips', 'favoriteable_id', '=', 'clips.id')
            ->whereHasMorph('favoriteable', Clip::class)
            ->whereBetween('favorites.created_at', $range[0])
            ->groupBy('clips.user_id')
            ->orderByDesc('favorites_count')
            ->first();
        $saved = $save ? User::query()->find($save->user_id) : null;
        $upload = Clip::query()
            ->select('clips.user_id')
            ->addSelect(DB::raw('COUNT(clips.id) AS clips_count'))
            ->whereBetween('clips.created_at', $range[0])
            ->groupBy('clips.user_id')
            ->orderByDesc('clips_count')
            ->first();
        $uploaded = $upload ? User::query()->find($upload->user_id) : null;
        $view = View::query()
            ->select('clips.user_id')
            ->addSelect(DB::raw('COUNT(viewable_id) AS views_count'))
            ->leftJoin('clips', 'viewable_id', '=', 'clips.id')
            ->whereHasMorph('viewable', Clip::class)
            ->whereBetween('viewed_at', $range[0])
            ->groupBy('clips.user_id')
            ->orderByDesc('views_count')
            ->first();
        $viewed = $view ? User::query()->find($view->user_id) : null;
        $data = compact('commented', 'followed', 'liked', 'saved', 'uploaded', 'viewed');
        return view('livewire.users-top-charts', ['results' => $data]);
    }

    public function update($mode)
    {
        $this->mode = $mode;
    }
}
