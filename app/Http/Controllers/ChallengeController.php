<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Http\Resources\Challenge as ChallengeResource;

class ChallengeController extends Controller
{
    public function index()
    {
        $advertisements = Challenge::query()->latest()->paginate(999);
        return ChallengeResource::collection($advertisements);
    }
}
