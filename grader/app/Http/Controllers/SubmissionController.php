<?php

namespace App\Http\Controllers;

use App\Http\Requests\Submission as SubmissionRequest;
use App\Submission;
use Auth;

class SubmissionController extends Controller
{
    public function store(SubmissionRequest $request)
    {
        $submission = Submission::make($request->all());

        Auth::user()->submissions()->save($submission);

        return 'success';
    }
}
