<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

            // Add status to each program course
            $student->program->courses->transform(function ($course) use ($courseStatusMap) {
                $course->status = $courseStatusMap[$course->id] ?? 'not_enrolled';
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
}
