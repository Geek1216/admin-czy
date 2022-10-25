<?php

namespace App\Http\Controllers;

use App\Clip;
use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $views = [];
        for ($i = 23; $i >= 0; $i--) {
            if ($i === 0) {
                $from = now()->startOfHour();
                $until = now()->endOfHour();
            } else {
                $from = now()->startOfHour()->subHours($i);
                $until = now()->endOfHour()->subHours($i);
            }
            $count = views(Clip::class)
                ->period(Period::create($from, $until))
                ->count();
            $label = sprintf(
                '%s - %s',
                $from->format('H:i'),
                $until->format('H:i')
            );
            $views[$label] = $count;
        }
        $devices = DB::table('devices')
            ->selectRaw("SUM(CASE WHEN platform = 'android' THEN 1 ELSE 0 END) AS Android")
            ->selectRaw("SUM(CASE WHEN platform = 'ios' THEN 1 ELSE 0 END) AS iOS")
            ->first();
        return view('home', compact('devices', 'views'));
    }
}
