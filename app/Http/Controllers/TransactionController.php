<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class TransactionController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $id = Auth::id();

        // Retrieve the student based on the authenticated user's ID
        $student = Student::where('user_id', $id)->first();
        $transactions = $student->transactions()->with('course')->latest()->get();

        return view('fees', compact('transactions'));
    }
}
