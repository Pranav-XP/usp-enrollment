<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Prerequisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Exception;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */

class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/student/program",
     *     summary="Get program information for authenticated student",
     *     tags={"Student"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Program retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="program", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Software Engineering")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Student not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    /**
     * @OA\Get(
     *     path="/api/student/program",
     *     summary="Get student's program and associated courses with status",
     *     tags={"Student"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Student program retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="program", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="program_code", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="courses", type="array", @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="course_code", type="string"),
     *                         @OA\Property(property="course_title", type="string"),
     *                         @OA\Property(property="year", type="integer"),
     *                         @OA\Property(property="status", type="string", example="enrolled")
     *                     ))
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student or program not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while fetching the program"
     *     )
     * )
     */
    public function getProgram()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $student = Student::where('user_id', $user->id)
                ->with(['courses', 'program.courses'])
                ->first();

            if (!$student || !$student->program) {
                return response()->json([
                    'message' => 'Student or program not found',
                ], 404);
            }

            // Map of enrolled course statuses
            $courseStatusMap = $student->courses->pluck('pivot.status', 'id');

            // Get the list of completed courses from the student's courses
            $completedCourses = $student->courses->filter(function ($course) {
                // Assuming 'pivot' holds the course status and checking for 'completed' status
                return $course->pivot->status === 'completed';
            })->pluck('course_code')->toArray();

            // Transform the courses
            $student->program->courses->transform(function ($course) use ($courseStatusMap, $completedCourses) {
                // Get the current course status (enrolled or not)
                $course->status = $courseStatusMap[$course->id] ?? 'not_enrolled';

                // Check if the course is enrolled (if status is 'enrolled')
                $isEnrolled = $course->status === 'enrolled';

                // If the course is enrolled, it should not be eligible
                if ($isEnrolled) {
                    $course->eligible = false;
                } else {
                    // Decode prerequisite groups for the course
                    $prerequisiteGroups = $course->prerequisites->pluck('prerequisite_groups')->flatten()->toArray();

                    // Check if the student meets the prerequisites for the course
                    $meetsPrerequisites = $this->checkPrerequisites($prerequisiteGroups, $completedCourses);

                    // If the student meets the prerequisites, mark as eligible
                    $course->eligible = $meetsPrerequisites;
                }

                // Return the modified course
                return $course;
            });

            return response()->json([
                'student' => $student,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the program',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if prerequisites are met based on the conditions.
     * @param array $prerequisiteGroups
     * @param array $completedCourses
     * @return bool
     */
    public function checkPrerequisites($prerequisiteGroups, $completedCourses)
    {
        foreach ($prerequisiteGroups as $group) {
            if (is_array($group)) {
                // OR condition: At least one course in the group must be completed
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


    /**
     * @OA\Get(
     *     path="/api/student/completed-courses",
     *     summary="Get a list of completed courses for the authenticated student",
     *     tags={"Student"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\Response(
     *         response=200,
     *         description="List of completed courses",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="completed_courses",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="course_code", type="string", example="CS111"),
     *                     @OA\Property(property="course_title", type="string", example="Introduction to Computing Science")
     *                 )
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Student not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student not found")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred while fetching completed courses."),
     *             @OA\Property(property="error", type="string", example="Exception message here")
     *         )
     *     )
     * )
     */

    public function completedCourses()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'message' => 'Student not found',
                ], 404);
            }

            $completedCourses = $student->courses()
                ->wherePivot('status', 'completed')
                ->select('courses.id as course_id', 'courses.course_code', 'courses.course_title')
                ->get();

            return response()->json([
                'completed_courses' => $completedCourses,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching completed courses.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
