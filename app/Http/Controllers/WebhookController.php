<?php

namespace App\Http\Controllers;

use App\Clip;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function sightengine(Clip $clip, Request $request)
    {
        $data = $request->input('data');
        if ($data['status'] === 'finished') {
            $score = [
                'y' => 0,
                'n' => 0,
            ];
            foreach ($data['frames'] as $frame) {
                if ($frame['nudity']['raw'] >= max($frame['nudity']['partial'], $frame['nudity']['safe'])) {
                    $score['y']++;
                } else {
                    $score['n']++;
                }
            }

            $porn = $score['y'] > 0 && $score['y'] >= $score['n'];
            $clip->approved = !$porn;
            $clip->save();
        }
    }
}
