<?php

namespace App\Http\Controllers;

use App\Http\Requests\Submission as SubmissionRequest;
use App\Submission;
use Auth;
use App;
use App\Blackboard;

class SubmissionController extends Controller
{
    public function create()
    {
        if (Auth::user()->submissions->isNotEmpty()) {
            return view('submitted');
        }

        return view('welcome');
    }

    public function store(SubmissionRequest $request)
    {
        $submission = Submission::make($request->all());

        Auth::user()->submissions()->save($submission);

        if (App::environment() !== 'testing') {
            (new Blackboard)->postGradeForStudent(Auth::user());
        }

        return view('submitted');
    }
}
