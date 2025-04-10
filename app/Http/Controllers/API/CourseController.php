<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Optionally, we can paginate the results or retrieve all records.
            $courses = Course::with(['prerequisites'])
                ->when($request->has('status'), function ($query) use ($request) {
                    // Filter by status if a 'status' parameter is provided
                    return $query->where('status', $request->status);
                })
                ->when($request->has('year'), function ($query) use ($request) {
                    // Filter by year if a 'year' parameter is provided
                    return $query->where('year', $request->year);
                })
                ->paginate(10); // You can change the number for pagination

            // Return the paginated list of courses
            return response()->json([
                'courses' => $courses,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the courses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Fetch the course with its related data, e.g., program and status
            $course = Course::with(['prerequisites'])
                ->where('id', $id)
                ->first(); // You can use firstOrFail to automatically return a 404 if not found

            // If course is not found, return a 404 response
            if (!$course) {
                return response()->json([
                    'message' => 'Course not found',
                ], 404);
            }

            // Return the course data with status information
            return response()->json([
                'course' => $course,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the course',
                'error' => $e->getMessage(),
            ], 500);
        }
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
