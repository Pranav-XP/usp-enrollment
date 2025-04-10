<?php

namespace App\Http\Controllers;

use App\EnrolmentStatus;
use App\Models\Course;
use App\Models\Prerequisite;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Str;


class EnrolmentController extends Controller
{

    public function dashboard()
    {
        // Get the authenticated user
        $id = Auth::id();

        // Retrieve the student based on the authenticated user's ID
        $student = Student::where('user_id', $id)->first();
        $programId = $student ? $student->program_id : null;

        if (!$student) {
            return view('dashboard');
        }


        $enrollmentSetting = Setting::where('key', 'users_can_enrol')->value('value');

        // Fetch the enrolled courses
        $enrolledCourses = $student->courses()->wherePivot('status', EnrolmentStatus::ENROLLED->value)->get();

        // Fetch the completed courses
        $completedCourses = $student->courses()->wherePivot('status', EnrolmentStatus::COMPLETED->value)->get();

        // Combine both enrolled and completed courses' IDs
        $excludedCourseIds = $enrolledCourses->pluck('id')->merge($completedCourses->pluck('id'))->toArray();

        $availableCourses = Course::whereNotIn('id', $excludedCourseIds)
            ->whereHas('programs', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })
            ->get();

        // Check prerequisites for each course and store the result
        $checkedCourses = $availableCourses->map(function ($course) use ($student) {
            $prerequisitesMet = $this->canEnrollInCourse($student->id, $course->id);
            $course->prerequisites_met = $prerequisitesMet; // Attach the status to the course
            return $course;
        });

        return view('dashboard', compact('enrolledCourses', 'checkedCourses', 'enrollmentSetting'));
    }

    public function enrolStudent(Request $request, $courseId)
    {
        // Get the authenticated user
        $id = Auth::id();

        // Retrieve the student based on the authenticated user's ID
        $student = Student::where('user_id', $id)->first();

        // Find the course by ID
        $course = Course::findOrFail($courseId);

        // Check if prerequisites are met
        if (!$this->canEnrollInCourse($student->id, $course->id)) {
            return redirect()->route('dashboard')->with('error', 'You do not meet the prerequisites for this course.');
        }

        // Enrol the student in the course
        $student->courses()->attach($course->id, ['status' => EnrolmentStatus::ENROLLED->value]);  // Adjust if necessary based on your pivot table

        Transaction::create([
            'student_id'       => $student->id,
            'course_id'        => $course->id,
            'reference_number' => Str::uuid(), // Generate a unique reference
            'amount'           => $course->cost, // Fetch course cost
            'status'           => 'completed', // Default status
        ]);

        // Redirect back to the dashboard with success message
        return redirect()->route('dashboard')->with('success', 'You have successfully enrolled in the course.');
    }

    function canEnrollInCourse($studentId, $courseId)
    {
        $enrollmentSetting = Setting::where('key', 'users_can_enrol')->value('value');


        if ($enrollmentSetting == '0') {
            return false;
        }


        $student = Student::find($studentId);

        if (!$student) {
            return false; // Student not found
        }

        // Get the student's completed courses (assumes "enrolled" means completed)
        $completedCourses = $student->courses();

        if (!$completedCourses) {
            return false;
        }

        // Step 2: Get the course and its prerequisites
        $course = Course::find($courseId);

        if (!$course) {
            return false; // Course not found
        }

        $yearCondition = $this->checkYearPrerequisite($student, $course);

        if (!$yearCondition) {
            return false;
        }

        $completedCourses = $student->courses()
            ->pluck('course_code')
            ->toArray();

        $prerequisiteGroups = Prerequisite::where('course_id', $courseId)->value('prerequisite_groups'); // Assuming a one-to-one relationship

        if (!$prerequisiteGroups) {
            return true;
        }

        // Decode prerequisites JSON
        $requiredCourses = $prerequisiteGroups;

        // Step 3: Check if prerequisites are met
        foreach ($requiredCourses as $group) {
            if (is_array($group)) {
                // OR condition: At least one course must be completed
                $meetsCondition = false;
                foreach ($group as $requiredCourse) {
                    if (in_array($requiredCourse, $completedCourses)) {
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
        $courseYear = ($course->year) - 1;

        if ($courseYear <= 0) {
            return true;
        }
        // Get all course IDs for the student's current year
        $yearCourses = Course::where('year', $courseYear)->pluck('id')->toArray();

        // Get the student's completed course IDs
        $completedCourses = $student->courses()->pluck("course_id")->toArray();


        // Initialize a counter for completed courses
        $completedCount = 0;

        foreach ($yearCourses as $yearCourse) {
            // Since $yearCourse is already an ID, compare it directly
            if (in_array($yearCourse, $completedCourses)) {
                $completedCount++;
            }
        }

        // Avoid division by zero
        $totalCourses = count($yearCourses);
        $completedPercentage = $totalCourses > 0 ? ($completedCount / $totalCourses) * 100 : 0;

        // Check if the student has completed at least 75% of the courses for the current year
        if ($completedPercentage >= 75) {
            return true;  // Student can move to the next year or enroll in the course
        }

        return false;  // Student cannot enroll in the course as prerequisites are not met
    }

    public function testEnrollment()
    {
        $result = $this->canEnrollInCourse(1, 11);
        if ($result) {
            return 'Hello Shiva'; // Return a string if true
        } else {
            return 'Hello shiva'; // Return a string if false
        }
    }
}
