<?php

namespace App\Http\Controllers;

use App\Aspects\LoggerAspect;
use App\Enums\GradeRecheckStatus;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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


    /**
     * Download the student's grade report as a PDF.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download()
    {
        // Get the authenticated student's ID
        $studentId = Auth::user()->student->id; // Assuming student() returns a student instance with an 'id'

        // Fetch the student details and their program separately
        $student = Student::with('program')->findOrFail($studentId);

        // Load only the completed course-student records for the specific student
        $completedCourseStudents = CourseStudent::where('student_id', $studentId)
            ->where('status', 'completed')
            ->with(['course', 'semester']) // Eager load the related Course and Semester models
            ->get();

        // Calculate GPA based on the loaded CourseStudent records
        $gradedCoursesCount = 0;
        $totalGradePoints = 0;

        foreach ($completedCourseStudents as $csRecord) {
            if ($csRecord->grade !== null) {
                $totalGradePoints += $csRecord->grade;
                $gradedCoursesCount += 1;
            }
        }

        $gpa = $gradedCoursesCount > 0 ? $totalGradePoints / $gradedCoursesCount : 0;

        $now = Carbon::now()->format('d F Y, h:i A');

        // Pass both student data and the course-student records to the PDF view
        return Pdf::view('grade-report', compact('student', 'completedCourseStudents', 'gpa', 'now'))
            ->name('grade_report_' . $student->student_id . '_' . Carbon::now()->format('YmdHis') . '.pdf')
            ->download();
    }
}
