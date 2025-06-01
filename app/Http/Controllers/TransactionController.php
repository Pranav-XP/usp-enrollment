<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class TransactionController extends Controller
{
    public function index()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Retrieve the student based on the authenticated user's ID
        $student = Student::where('user_id', $userId)->first();

        // If no student is found for the authenticated user, return an empty view.
        if (!$student) {
            return view('fees', [
                'transactionsBySemester' => collect(), // Return an empty collection for grouping
                'totalAmount' => 0
            ]);
        }

        // Eager load the 'courses' and 'semester' relationships on transactions.
        // We also want to order transactions by semester year and term for logical grouping.
        $transactions = $student->transactions()
            ->with(['courses', 'semester'])
            ->join('semesters', 'transactions.semester_id', '=', 'semesters.id')
            ->orderBy('semesters.year', 'desc') // Order by year descending
            ->orderBy('semesters.term', 'desc') // Then by term descending
            ->select('transactions.*') // Select all transaction columns to avoid conflicts with join
            ->get();

        // Group transactions by semester name
        // This will create a collection where each key is the semester name
        // and the value is a collection of transactions for that semester.
        $transactionsBySemester = $transactions->groupBy(function ($transaction) {
            return $transaction->semester->name ?? 'Unassigned Semester'; // Use 'Unassigned Semester' if semester is null
        });

        // Calculate the total amount for all transactions
        $totalAmount = $transactions->sum('amount');

        // Pass the grouped transactions and the total amount to the view
        return view('fees', compact('transactionsBySemester', 'totalAmount'));
    }
}
