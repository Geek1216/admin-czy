<?php

namespace App\Http\Controllers;

use App\Clip;
use App\Story;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function store(Request$request)
    {
        $data = $this->validate($request, [
            'subject_id' => ['required', 'integer'],
            'subject_type' => ['required', 'string', 'in:clip,story,comment,user'],
            'reason' => ['required', 'string', Rule::in(array_keys(config('fixtures.report_reasons')))],
            'message' => ['nullable', 'string', 'max:160'],
        ]);
        switch ($data['subject_type']) {
            case 'clip':
                $subject = Clip::findOrFail($data['subject_id']);
                $this->authorize('view', $subject);
                break;
            case 'story':
                $subject = Story::findOrFail($data['subject_id']);
                $this->authorize('view', $subject);
                break;
            case 'comment':
                $subject = Comment::findOrFail($data['subject_id']);
                break;
            case 'user':
                $subject = User::findOrFail($data['subject_id']);
                break;
        }
        /** @var User $user */
        $user = $request->user();
        $report = $user->reportedReport()->make($data);
        $report->subject_id = $subject->getKey();
        $report->subject_type = $subject->getMorphClass();
        $report->status = 'received';
        $report->save();
    }
}
