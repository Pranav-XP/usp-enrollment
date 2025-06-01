<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentHoldViewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Eager load holds so we can use accessors without N+1 queries
        $student = Student::with(['holds' => function ($query) {
            $query->with('placedBy'); // Eager load the admin who placed the hold
        }])->where('user_id', $user->id)->firstOrFail();

        // Access active hold directly using the accessor
        $activeHold = $student->active_hold;

        // Fetch all past and active holds for history
        $allHolds = $student->holds->sortByDesc('placed_at');

        return view('holds', compact('student', 'activeHold', 'allHolds'));
    }
}
