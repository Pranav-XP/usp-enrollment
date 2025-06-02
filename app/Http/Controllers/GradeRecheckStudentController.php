<?php

namespace App\Http\Controllers;

use App\Enums\EnrolmentStatus;
use App\Enums\GradeRecheckStatus;
use App\Mail\RecheckApplicationSubmittedMail;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\GradeRecheckApplication;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GradeRecheckStudentController extends Controller
{
    /**
     * Show the form for creating a new grade recheck application.
     *
     * @param int $courseId The ID of the course to recheck.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(int $courseId)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $course = Course::findOrFail($courseId);

        // Ensure the student has completed this course and it has a grade
        $enrollment = CourseStudent::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', EnrolmentStatus::COMPLETED->value) // Ensure it's completed
            ->first();

        if (!$enrollment || $enrollment->grade === null) { // Access grade directly
            return redirect()->route('grades')->with('error', 'You can only recheck grades for completed courses with an assigned grade.');
        }

        // Check if there's an existing pending application for this course by this student
        $existingApplication = GradeRecheckApplication::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', GradeRecheckStatus::PENDING->value)
            ->first();

        if ($existingApplication) {
            return redirect()->route('grades')->with('info', 'You already have a pending recheck application for this course.');
        }


        // Pass student, course, and their enrollment details to the form
        return view('recheck-form', compact('student', 'course', 'enrollment'));
    }

    /**
     * Store a newly created grade recheck application in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $course = Course::findOrFail($request->input('course_id'));

        // Validate incoming request data
        $request->validate([
            'course_id'              => 'required|exists:courses,id',
            'full_name'              => 'required|string|max:255',
            'postal_address'         => 'nullable|string|max:500',
            'date_of_birth'          => 'nullable|date',
            'telephone'              => 'required|string|max:50',
            'email'                  => 'required|email|max:255',
            'sponsorship_status'     => ['required', 'string', Rule::in(['Private', 'Sponsored'])],
            'course_lecturer_name'   => 'required|string|max:255',
            'receipt_no'             => 'required|string|max:255',
            'payment_confirmation_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072', // Max 3MB (3072 KB)
        ]);

        // Handle file upload with custom naming to PRIVATE DISK
        $paymentConfirmationPath = null;
        if ($request->hasFile('payment_confirmation_upload')) {
            $file = $request->file('payment_confirmation_upload');
            $extension = $file->getClientOriginalExtension();
            $filename = "{$student->student_id}_courseid_{$course->id}_recheck_reciept." . $extension;

            // Store the file in 'storage/app/recheck_payments' (default local disk)
            // It is explicitly set to 'local' for clarity, but omitting it defaults to 'local'.
            $path = $file->storeAs('recheck_payments', $filename, 'local');

            // The path stored in the database is now the internal storage path,
            // NOT a public URL. This path starts from the disk's root (e.g., 'recheck_payments/s123_1_recheck.pdf')
            $paymentConfirmationPath = $path;
        }

        // Create the application
        $application = GradeRecheckApplication::create([
            'student_id'                => $student->id,
            'course_id'                 => $course->id,
            'full_name'                 => $request->full_name,
            'postal_address'            => $request->postal_address,
            'date_of_birth'             => $request->date_of_birth,
            'telephone'                 => $request->telephone,
            'email'                     => $request->email,
            'sponsorship_status'        => $request->sponsorship_status,
            'course_code'               => $course->course_code, // Populate from Course model
            'course_title'              => $course->course_title, // Populate from Course model
            'course_lecturer_name'      => $request->course_lecturer_name,
            'receipt_no'                => $request->receipt_no,
            'payment_confirmation_path' => $paymentConfirmationPath,
            'status'                    => GradeRecheckStatus::PENDING, // Default status
        ]);

        // --- Send Email Confirmation to Student ---
        Mail::to($application->email)->send(new RecheckApplicationSubmittedMail($application));

        return redirect()->route('grades')->with('success', 'Your grade recheck application has been submitted successfully!');
    }
}
