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

        // Check if student exists to prevent errors if no student is found for the user
        if (!$student) {
            return view('fees', ['transactions' => collect(), 'totalAmount' => 0]);
        }

        // Eager load the 'courses' relationship on transactions
        $transactions = $student->transactions()->with('courses')->latest()->get();

        // Calculate total amount
        $totalAmount = $transactions->sum('amount');

        return view('fees', compact('transactions', 'totalAmount'));
    }
}
