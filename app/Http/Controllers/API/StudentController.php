<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Services\PrerequisiteService;
use Exception;
use Illuminate\Support\Facades\DB;

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

    protected $prerequisiteService;

    // Inject PrerequisiteService into the controller
    public function __construct(PrerequisiteService $prerequisiteService)
    {
        $this->prerequisiteService = $prerequisiteService;
    }
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

            $student->program->courses->transform(function ($course) use ($courseStatusMap, $completedCourses, $student) {
                // Assign course status
                $course->status = $courseStatusMap[$course->id] ?? 'not_enrolled';

                $isEnrolled = $course->status === 'enrolled';
                $isCompleted = $course->status === 'completed';

                // Default to not eligible
                $course->eligible = false;

                if (!$isEnrolled && !$isCompleted) {
                    // Decode prerequisite groups
                    $prerequisiteGroups = $course->prerequisites->pluck('prerequisite_groups')->flatten()->toArray();

                    // Check if both regular and year prerequisites are satisfied
                    $meetsPrerequisites = $this->prerequisiteService->checkPrerequisites($prerequisiteGroups, $completedCourses);
                    $meetsYearRequirement = $this->prerequisiteService->checkYearPrerequisite($student, $course);

                    // Mark course as eligible if both are satisfied
                    $course->eligible =  $meetsPrerequisites && $meetsYearRequirement;
                }

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

    public function markAllCourses()
    {
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

        // Get all courses for this student (using the correct relationship)
        $courses = $student->courses()->get(); // Ensure this returns a collection of courses, not a query builder

        // Begin transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Loop through each course and set its completion status and GPA
            foreach ($courses as $course) {
                // Fetch the student-course relationship from the pivot table (if exists)
                $studentCourse = $student->courses()->where('course_id', $course->id)->first();

                // Check if a student-course relationship was found
                if ($studentCourse) {
                    // Assuming `student_course` is the pivot table
                    // Set status and GPA on the pivot table
                    $studentCourse->pivot->status = 'completed'; // Or whatever status represents completion
                    $studentCourse->pivot->grade = 3.5; // Set GPA to 3.5

                    // Save the updated pivot record
                    $studentCourse->pivot->save();
                } else {
                    // If no student-course relationship is found, throw an error
                    throw new \Exception("Student is not enrolled in the course {$course->id}");
                }
            }

            // Commit the transaction
            DB::commit();

            // Return a success message
            return response()->json(['message' => 'All courses marked as completed with GPA 3.5'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Return an error response with the exception message
            return response()->json([
                'message' => 'An error occurred while updating courses',
                'error' => $e->getMessage() // Return the actual error message
            ], 500);
        }
    }
}
