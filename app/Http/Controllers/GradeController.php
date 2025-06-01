<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {

        // Get the authenticated user
        $id = Auth::id();

        // Retrieve the student based on the authenticated user's ID
        $student = Student::where('user_id', $id)->first();
        // Fetch student and their courses with grades
        $student = Student::with('courses')->findOrFail($student->id);

        return view('grades', compact('student'));
    }
}
