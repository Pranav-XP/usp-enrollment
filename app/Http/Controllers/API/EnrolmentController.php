<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Prerequisite;
use App\Models\Student;
use App\Models\Transaction;
use App\Services\PrerequisiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrolmentController extends Controller
{

    protected $prerequisiteService;

    // Inject the PrerequisiteService into the controller
    public function __construct(PrerequisiteService $prerequisiteService)
    {
        $this->prerequisiteService = $prerequisiteService;
    }

    public function enrol(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $student = Student::where('user_id', $user->id)->firstOrFail();
        $courseIds = $request->course_ids;

        DB::beginTransaction();

        try {
            $enrolledCourses = [];
            $skippedCourses = []; // To track the courses that were skipped due to prerequisites not being met

            foreach ($courseIds as $courseId) {
                $course = Course::findOrFail($courseId);

                // Check if prerequisites are met
                $prerequisitesMet = $this->checkCoursePrerequisites($course, $student);


                if ($prerequisitesMet) {

                    // Enroll the student if not already enrolled or completed
                    $student->courses()->attach($courseId, ['status' => 'enrolled']);
                    $enrolledCourses[] = $courseId;
                } else {
                    // If prerequisites are not met, add the course to skipped list
                    $skippedCourses[] = $courseId;
                }
            }

            // Commit the database transaction after all enrollments
            DB::commit();

            // Return the results
            $response = [
                'message' => count($enrolledCourses) > 0 ? 'Enrolled successfully.' : 'No courses were enrolled.',
                'enrolled_courses' => $enrolledCourses,
            ];

            // If there are skipped courses, return them with a 400 error code
            if (count($skippedCourses) > 0) {
                $response['skipped_courses'] = $skippedCourses;
                return response()->json($response, 400);  // 400 Bad Request for skipped courses due to unmet prerequisites
            }

            // If no courses were enrolled, return a different message
            if (count($enrolledCourses) === 0) {
                $response['message'] = 'No courses were successfully enrolled.';
                return response()->json($response, 400);  // 400 Bad Request to indicate no courses were enrolled
            }

            return response()->json($response, 200);  // 200 OK for successful enrollment if courses were enrolled

        } catch (\Exception $e) {
            DB::rollBack();
            // Return a 500 Internal Server Error if something went wrong
            return response()->json([
                'message' => 'Enrollment failed.',
                'error' => $e->getMessage(),
            ], 500);  // 500 Internal Server Error for unexpected issues
        }
    }


    /**
     * Check if the student meets the prerequisites for a given course.
     *
     * @param \App\Models\Course $course
     * @param \App\Models\Student $student
     * @return bool
     */
    protected function checkCoursePrerequisites($course, $student)
    {
        // Get completed courses for the student
        $completedCourses = $student->courses()
            ->pluck('course_code')
            ->toArray();

        if (!$completedCourses) {
            return false;
        }

        if (!$course) {
            return false;
        }

        // Check if the student meets the year prerequisite
        $yearCondition = $this->prerequisiteService->checkYearPrerequisite($student, $course);

        if (!$yearCondition) {
            return false;
        }

        // Get prerequisite groups for the course
        $requiredCourses = Prerequisite::where('course_id', $course->id)->value('prerequisite_groups');

        if (!$requiredCourses) {
            return true; // No prerequisites to check, so return true
        }


        // Step 3: Check if prerequisites are met using the isGroupMet function
        foreach ($requiredCourses as $group) {
            $isMet = $this->checkPrerequisites($completedCourses, $group);  // Use the isGroupMet function here

            if (!$isMet) {
                return false; // Student does not meet the prerequisites for this group
            }
        }

        return true; // Student meets all prerequisites
    }

    function checkPrerequisites($studentCourses, $requiredCourses)
    {
        foreach ($requiredCourses as $group) {
            if (is_array($group)) {
                // OR condition: At least one of these courses must be completed
                $meetsCondition = false;
                foreach ($group as $course) {
                    if (in_array($course, $studentCourses)) {
                        $meetsCondition = true;
                        break;
                    }
                }
                if (!$meetsCondition) {
                    return false; // Student does not meet this OR condition
                }
            } else {
                // AND condition: Every required course must be completed
                if (!in_array($group, $studentCourses)) {
                    return false;
                }
            }
        }
        return true; // Student meets all prerequisite groups
    }

    // Function to check if the group of courses is met
    function isGroupMet($group, $completedCourses)
    {
        if (is_array($group)) {
            // If any course in the OR group is completed, it's met
            foreach ($group as $code) {
                foreach ($completedCourses as $course) {
                    if ($course === $code) {
                        return true;
                    }
                }
            }
        } else {
            // Check if the single course code is completed
            foreach ($completedCourses as $course) {
                if ($course === $group) {
                    return true;
                }
            }
        }

        return false;
    }
}
