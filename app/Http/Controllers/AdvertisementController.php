<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Http\Resources\Advertisement as AdvertisementResource;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::query()->latest()->paginate(999);
        return AdvertisementResource::collection($advertisements);
    }
}
