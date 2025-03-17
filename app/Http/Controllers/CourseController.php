<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class CourseController extends Controller
{
    public function index()
{
    $programs = Program::all(); // Fetch all programs
    return view('courses', compact('programs'));
}
}
