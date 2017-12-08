<?php

namespace App\Http\Controllers;

use App\Http\Requests\Submission;

class GraderController extends Controller
{
    public function grade(Submission $request)
    {
        return 'success';
    }
}
