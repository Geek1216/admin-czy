<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\Category as CategoryResource;
use App\Jobs\SendNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class);
    }

    public function index(Request $request)
    {
        /** @var Builder $query */
        $query = Category::query();
        $category = $query
                ->orderByDesc('id')
                ->paginate($request->get('count', 15));

        return CategoryResource::collection($category);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'category_name' => ['string'],
        ]);
        $data['category_name'] = $data['category_name'] ?? false;
        
        // $category = $user->groups()->make($data);
        $category->save();
        // SendNotification::dispatch(
        //     __('notifications.posted_new_clip.title', ['user' => $user->username]),
        //     __('notifications.posted_new_clip.body'),
        //     null,
        //     $user,
        //     $clip,
        //     null,
        //     true
        // );
        return CategotyResource::make($category);
    }

    public function show(Category $category)
    {
        $resource = CategotyResource::make($category);
        views($category)->record();
        return $resource;
    }

    public function update(Category $category, Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['string'],
        ]);
        $category->fill($data);
        $category->save();

        return CategoryResource::make($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }
}
