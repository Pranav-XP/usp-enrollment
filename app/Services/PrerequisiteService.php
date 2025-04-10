<?php

namespace App\Services;

use App\Models\Course;

class PrerequisiteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
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

    function checkYearPrerequisite($student, $course)
    {
        $courseYear = ($course->year) - 1;

        if ($courseYear <= 0) {
            return true; // No year-based prerequisite required
        }

        $yearCourses = Course::where('year', $courseYear)->pluck('id')->toArray();
        // Get the student's completed course IDs
        $completedCourses = $student->courses()->pluck("course_id")->toArray();

        // Count how many of the previous year's courses are completed
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
}
