<?php

namespace App\Http\Controllers;

use App\Aspects\LoggerAspect;
use App\Enums\EnrolmentStatus;
use App\Models\Course;
use App\Models\Prerequisite;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Setting;
// use App\Models\Transaction; // No longer directly used for creation here
use App\Services\TransactionService; // Import the TransactionService
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Str; // No longer directly used for UUID generation here

class EnrolmentController__AopProxied extends Controller
{
    public $transactionService;

    // Constructor to inject the service
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function dashboard(Request $request)
    {
        $id = Auth::id();
        $student = Student::where('user_id', $id)->first();
        $programId = $student ? $student->program_id : null;

        if (!$student) {
            return view('dashboard')->with('error', 'Your student profile could not be found.');
        }

        $activeSemester = Semester::getActiveSemester();

        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Enrollment is currently closed or no active semester is defined by the administration.');
        }

        $enrollmentSetting = Setting::where('key', 'users_can_enrol')->value('value');

        $enrolledCourses = $student->courses()
            ->wherePivot('status', EnrolmentStatus::ENROLLED->value)
            ->wherePivot('semester_id', $activeSemester->id)
            ->get();

        $completedCourses = $student->courses()
            ->wherePivot('status', EnrolmentStatus::COMPLETED->value)
            ->get();

        $excludedCourseIds = $enrolledCourses->pluck('id')->merge($completedCourses->pluck('id'))->toArray();

        $semesterColumn = 'semester_' . $activeSemester->term;

        $availableCourses = Course::whereNotIn('id', $excludedCourseIds)
            ->whereHas('programs', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })
            ->where($semesterColumn, true)
            ->get();

        $checkedCourses = $availableCourses->map(function ($course) use ($student) {
            $prerequisitesMet = $this->canEnrollInCourse($student->id, $course->id);
            $course->prerequisites_met = $prerequisitesMet;
            return $course;
        });

        return view('dashboard', compact('enrolledCourses', 'checkedCourses', 'enrollmentSetting', 'activeSemester'));
    }

    #[LoggerAspect]
    public function enrolStudent(Request $request, $courseId)
    {
        $id = Auth::id();
        $student = Student::where('user_id', $id)->first();

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student record not found.');
        }

        $activeSemester = Semester::getActiveSemester();
        $activeSemesterId = $activeSemester->id;

        if (!$activeSemester) {
            return redirect()->route('dashboard')->with('error', 'Enrollment is currently closed or no active semester is defined.');
        }

        $course = Course::findOrFail($courseId);

        $semesterColumn = 'semester_' . $activeSemester->term;

        if (!($course->{$semesterColumn})) {
            return redirect()->route('dashboard')->with('error', 'This course is not offered in the ' . $activeSemester->name . '.');
        }

        $alreadyEnrolled = $student->courses()
            ->where('course_id', $courseId)
            ->wherePivot('semester_id', $activeSemesterId)
            ->exists();
        if ($alreadyEnrolled) {
            return redirect()->route('dashboard')->with('error', 'You are already enrolled in this course for ' . $activeSemester->name . '.');
        }

        if (!$this->canEnrollInCourse($student->id, $course->id)) {
            return redirect()->route('dashboard')->with('error', 'You do not meet the prerequisites for this course or enrollment is closed.');
        }

        // --- Enrollment (Pivot Table) ---
        $student->courses()->attach($course->id, [
            'status' => EnrolmentStatus::ENROLLED->value,
            'semester_id' => $activeSemester->id,
        ]);

        // --- Transaction Creation (using the Service) ---
        try {
            $this->transactionService->createEnrollmentTransaction(
                $student,
                $course, // Pass the Course model
                $activeSemesterId,
                $course->cost, // Explicitly pass the cost if it's always course.cost
                'pending'
            );
        } catch (\InvalidArgumentException $e) {
            // Log the error for debugging
            Log::error("Transaction creation failed during enrollment for student {$student->id}, course {$course->id}: " . $e->getMessage());
            // Redirect with an error message
            return redirect()->route('dashboard')->with('error', 'Failed to create transaction for enrollment: ' . $e->getMessage());
        }


        return redirect()->route('dashboard')->with('success', 'You have successfully enrolled in ' . $course->course_code . ' for ' . $activeSemester->name . '.');
    }

    function canEnrollInCourse($studentId, $courseId)
    {
        $enrollmentSetting = Setting::where('key', 'users_can_enrol')->value('value');

        if ($enrollmentSetting == '0') {
            return false; // Enrollment is globally closed
        }

        $student = Student::find($studentId);
        if (!$student) {
            return false;
        }

        $course = Course::find($courseId);
        if (!$course) {
            return false;
        }

        // Check year prerequisite first
        if (!$this->checkYearPrerequisite($student, $course)) {
            return false;
        }

        // Get student's completed courses (all semesters) by course code
        $completedCourses = $student->courses()
            ->wherePivot('status', EnrolmentStatus::COMPLETED->value)
            ->pluck('course_code') // Ensure you are plucking 'course_code' if 'prerequisite_groups' uses codes
            ->toArray();

        // Get prerequisite groups for the course
        // Assuming Prerequisite model has 'prerequisite_groups' as a JSON-decoded array
        $prerequisiteEntry = Prerequisite::where('course_id', $courseId)->first();
        $prerequisiteGroups = $prerequisiteEntry ? $prerequisiteEntry->prerequisite_groups : null;


        // If no specific prerequisites are defined, student can enroll (based on year check)
        if (empty($prerequisiteGroups)) { // Use empty to check for null or empty array
            return true;
        }

        // Check prerequisite groups (AND/OR logic)
        foreach ($prerequisiteGroups as $group) {
            if (is_array($group)) {
                // OR condition: At least one course in the group must be completed
                $meetsCondition = false;
                foreach ($group as $requiredCourseCode) {
                    if (in_array($requiredCourseCode, $completedCourses)) {
                        $meetsCondition = true;
                        break;
                    }
                }
                if (!$meetsCondition) {
                    return false; // Student does not meet this OR condition
                }
            } else {
                // AND condition: Every course in the list must be completed
                if (!in_array($group, $completedCourses)) {
                    return false; // Student did not complete a required course
                }
            }
        }

        return true; // Student meets all prerequisites
    }

    function checkYearPrerequisite($student, $course)
    {
        // Assuming 'year' on Course model refers to the recommended study year for that course (e.g., 1, 2)

        $courseTargetYear = $course->year; // e.g., 1 for Year 1 course, 2 for Year 2 course

        // If the course is a Year 1 course, no year prerequisite check applies from previous years.
        if ($courseTargetYear <= 1) {
            return true;
        }

        $previousYear = $courseTargetYear - 1;

        // Get all courses from the *previous* year that are part of the student's program
        $previousYearCoursesInProgram = Course::where('year', $previousYear)
            ->whereHas('programs', function ($query) use ($student) {
                $query->where('program_id', $student->program_id);
            })->pluck('id')->toArray();

        // If there are no courses defined for the previous year in the program,
        // it's considered that the prerequisite is met, preventing false negatives.
        if (empty($previousYearCoursesInProgram)) {
            return true;
        }

        // Get the student's completed course IDs (from all semesters)
        $completedCoursesIds = $student->courses()->wherePivot('status', EnrolmentStatus::COMPLETED->value)->pluck('course_id')->toArray();

        // Count how many of the previous year's courses the student has completed
        $completedCount = 0;
        foreach ($previousYearCoursesInProgram as $yearCourseId) {
            if (in_array($yearCourseId, $completedCoursesIds)) {
                $completedCount++;
            }
        }

        $totalCoursesInPreviousYear = count($previousYearCoursesInProgram);
        $completedPercentage = $totalCoursesInPreviousYear > 0 ? ($completedCount / $totalCoursesInPreviousYear) * 100 : 0;

        // Check if the student has completed at least 75% of the courses for the previous year
        return $completedPercentage >= 75;
    }
}

include_once '/Users/pranav/Code/usp-enrollment/cache/aop/woven/app/Http/Controllers/EnrolmentController.php';