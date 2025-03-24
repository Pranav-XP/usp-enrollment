<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;


class CourseController extends Controller
{
    public function index()
{
    // Get the authenticated user
    $id = Auth::id();

    // Retrieve the student based on the authenticated user's ID
    $student = Student::where('user_id', $id)->first();
    if (!$student) {
        return redirect()->back()->with('error', 'Student record not found. Contact SAS');
    }

    // Fetch the student's program with its courses
    $program = Program::where('id', $student->program_id)
            ->with(['courses.prerequisites']) // Load prerequisites
            ->first();

    if (!$program) {
        return redirect()->back()->with('error', 'No program found for this student. Contact SAS');
    }

    return view('courses', compact('program'));;
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
