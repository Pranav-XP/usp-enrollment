<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\HoldPlacedNotification;
use App\Mail\HoldReleasedNotification;
use App\Models\Student;
use App\Models\StudentHold;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class StudentHoldController extends Controller
{
    /**
     * Display a list of holds for a specific student.
     */
    public function index(Student $student)
    {
        $holds = $student->holds()->latest()->get();
        return view('admin.holds', compact('student', 'holds'));
    }

    /**
     * Show the form for creating a new hold.
     */
    public function create(Student $student)
    {
        // Check if student already has an active hold before showing form
        if ($student->is_on_hold) {
            return redirect()->route('admin.holds.index', $student)
                ->with('error', 'This student already has an active hold. Please release it before placing a new one.');
        }
        return view('admin.create-holds', compact('student'));
    }

    /**
     * Store a newly created hold in storage.
     */
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            // StudentHold::create() method now contains the logic to prevent multiple active holds.
            $hold = $student->holds()->create([
                'student_id' => $student->id,
                'reason' => $request->input('reason'),
                'placed_at' => Carbon::now(),
                'is_active' => true,
            ]);

            Mail::to($student->email)->send(new HoldPlacedNotification($student, $hold));

            return redirect()->route('admin.holds.index', $student)
                ->with('success', 'Hold successfully placed on ' . $student->first_name . ' ' . $student->last_name . '.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', $e->getMessage()); // Display the exception message
        }
    }

    /**
     * Release an active hold.
     */
    public function release(StudentHold $hold)
    {

        if ($hold->is_active) {
            $hold->update([
                'is_active' => false,
                'released_at' => Carbon::now(),
            ]);

            $student = $hold->student;

            Mail::to($student->email)->send(new HoldReleasedNotification($student, $hold));
            return redirect()->route('admin.holds.index', $hold->student)
                ->with('success', 'Hold on ' . $hold->student->first_name . ' ' . $hold->student->last_name . ' has been released.');
        }

        return redirect()->route('admin.holds.index', $hold->student)
            ->with('error', 'Hold is already inactive.');
    }
}
