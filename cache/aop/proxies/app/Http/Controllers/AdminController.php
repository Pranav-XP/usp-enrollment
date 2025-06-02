<?php

namespace App\Http\Controllers;

use App\Aspects\LoggerAspect;
use App\Enums\EnrolmentStatus;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;

use Illuminate\Support\Facades\DB;

class AdminController__AopProxied extends Controller
{
    // Show list of students
    public function showStudentsList()
    {
        $students = Student::all();
        return view('admin.students', compact('students'));
    }

    // Show grade form for a student and a course
    #[LoggerAspect]
    public function showGradeForm($studentId)
    {
        // Fetch the student and their enrolled courses with the pivot table data (e.g. grade, status)
        $student = Student::findOrFail($studentId);
        // Fetch enrolled courses
        $enrolledCourses = $student->courses()->get();

        return view('admin.update-grade', compact('student', 'enrolledCourses'));
    }

    #[LoggerAspect]
    public function updateGrades(Request $request, $studentId)
    {
        // Validate the incoming request data
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:4.5', // Grades should be numeric between 0 and 4.5, can be null
        ]);

        $student = Student::findOrFail($studentId);
        $gradesInput = $request->input('grades'); // Rename to avoid conflict with loop variable

        // Start a database transaction for atomicity
        DB::beginTransaction();

        try {
            $updatedCount = 0;
            $errors = [];

            // Get all courses the student is currently enrolled in,
            // specifically those with a 'grade' pivot attribute.
            // This ensures we only attempt to update valid existing enrollments.
            $currentEnrollments = $student->courses()->wherePivotIn('course_id', array_keys($gradesInput))->get()->keyBy('pivot.course_id');

            foreach ($gradesInput as $courseId => $gradeValue) {
                // Ensure the grade value is treated as a float
                $grade = is_numeric($gradeValue) ? (float)$gradeValue : null;

                // Check if the student is actually enrolled in this course and the pivot exists
                if ($currentEnrollments->has($courseId)) {
                    // Determine status based on the provided grade
                    $status = ($grade !== null && $grade < 2.0) ? EnrolmentStatus::FAILED->value : EnrolmentStatus::COMPLETED->value;
                    // Note: Changed fail threshold to < 2.0 (C) as per your grading scale (0=E, 1.0=D, 1.5=R)
                    // If you want 3.0 (B) as the pass/fail threshold, keep your original condition.

                    $student->courses()->updateExistingPivot($courseId, [
                        'grade' => $grade,
                        'status' => $status,
                    ]);
                    $updatedCount++;
                } else {
                    $errors[] = "Student is not enrolled in Course ID: {$courseId}. Grade update skipped.";
                }
            }

            DB::commit(); // Commit the transaction if all updates are successful

            $message = "Successfully updated grades for {$student->first_name} {$student->last_name}";
            if (!empty($errors)) {
                $message .= " Some updates were skipped: " . implode(", ", $errors);
                return redirect()->route('admin.students')->with('error', $message);
            }

            return redirect()->route('admin.students')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            return redirect()->back()->with('error', 'Failed to update grades: ' . $e->getMessage());
        }
    }
}

include_once '/Users/pranav/Code/usp-enrollment/cache/aop/woven/app/Http/Controllers/AdminController.php';