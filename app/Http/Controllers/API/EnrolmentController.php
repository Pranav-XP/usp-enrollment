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
                $prerequisitesMet = $this->prerequisiteService->checkCoursePrerequisites($course, $student);


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

    public function check(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $student = $user->student;

        if (!$student) {
            return response()->json([
                'message' => 'Student not found'
            ], 404);
        }

        $course = Course::find($request->course_id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }

        // Get completed courses for the student (by course code)
        $completedCourses = $student->courses()
            ->pluck('course_code')
            ->toArray();

        // Check if the student already completed this course
        $alreadyCompleted = in_array($course->course_code, $completedCourses);

        // Check if prerequisites are met
        $prerequisitesMet = $this->checkCoursePrerequisites($course, $student);

        $eligible = !$alreadyCompleted && $prerequisitesMet;

        return response()->json([
            'eligible' => $eligible,
            'already_completed' => $alreadyCompleted,
            'prerequisites_met' => $prerequisitesMet,
            'message' => $eligible
                ? 'Student is eligible for this course'
                : (
                    $alreadyCompleted
                    ? 'Student has already completed this course'
                    : 'Prerequisites not met for this course'
                )
        ]);
    }
}
