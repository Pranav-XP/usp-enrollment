<?php

namespace App\Http\Controllers;

use App\Aspects\LoggerAspect;
use App\Enums\GradeRecheckStatus;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{

    #[LoggerAspect]
    public function index()
    {
        $userId = Auth::id();

        // Eager load 'courses' and 'recheckApplications'.
        // For recheckApplications, only fetch those with a 'pending' status.
        $student = Student::where('user_id', $userId)
            ->with([
                'courses',
                'recheckApplications' => function ($query) {
                    $query->where('status', GradeRecheckStatus::PENDING->value);
                }
            ])
            ->firstOrFail();

        return view('grades', compact('student'));
    }
}
