<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
{
    $programs = Program::all(); // Fetch all programs
    return view('courses', compact('programs'));
}

public function showGrades()
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
