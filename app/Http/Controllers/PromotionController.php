<?php

namespace App\Http\Controllers;

use App\Http\Resources\Promotion as PromotionResource;
use App\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();
        $after = $request->get('after');
        if (is_numeric($after)) {
            $query = $query->where('created_at', '>=', Carbon::createFromTimestampMs($after));
        } else {
            $query = $query->where('sticky', true);
        }

        return PromotionResource::collection($query->latest()->paginate(999));
    }
}
