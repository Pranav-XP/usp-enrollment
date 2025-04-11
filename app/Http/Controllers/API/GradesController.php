<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $student = $user->student;

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.',
                ], 404);
            }

            $courses = $student->courses()
                ->withPivot('grade', 'status')
                ->wherePivot('status', 'completed')
                ->get();

            $grades = $courses->map(function ($course) {
                return [
                    'course_id'    => $course->id,
                    'course_code'  => $course->course_code,
                    'course_name'  => $course->course_title,
                    'gpa'          => $course->pivot->grade,
                    'status'       => $course->pivot->status,
                    'year'         => $course->year,
                    'semester_1'   => $course->semester_1,
                    'semester_2'   => $course->semester_2,
                ];
            });

            // Calculate total GPA
            $validGrades = $courses->filter(function ($course) {
                return $course->pivot->grade !== null;
            });

            $totalGpa = $validGrades->count() > 0
                ? round($validGrades->sum(fn($c) => $c->pivot->grade) / $validGrades->count(), 2)
                : null;

            return response()->json([
                'grades'    => $grades,
                'total_gpa' => $totalGpa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching grade data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
