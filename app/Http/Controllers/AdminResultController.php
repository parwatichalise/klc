<?php

namespace App\Http\Controllers;
use App\Models\UserResult;

use Illuminate\Http\Request;

class AdminResultController extends Controller
{
    public function showStudentResults()
    {
        $results = UserResult::all(); 

        return view('admin.studentResult', compact('results'));
    }
}
