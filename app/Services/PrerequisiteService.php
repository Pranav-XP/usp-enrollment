<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Prerequisite;

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
     * Check if the student meets the prerequisites for a given course.
     *
     * @param \App\Models\Course $course
     * @param \App\Models\Student $student
     * @return bool
     */
    public function checkCoursePrerequisites($course, $student)
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
        $yearCondition = $this->checkYearPrerequisite($student, $course);

        if (!$yearCondition) {
            return false;
        }

        // Get prerequisite groups for the course
        $prerequisiteGroups = Prerequisite::where('course_id', $course->id)->value('prerequisite_groups');

        if (!$prerequisiteGroups) {
            return true; // No prerequisites to check, so return true
        }

        // Decode JSON into array
        $requiredCourses = $prerequisiteGroups;



        $isMet = $this->checkPrerequisites($completedCourses, $requiredCourses);  // Use the isGroupMet function here
        return $isMet && $yearCondition;
    }

    protected function checkPrerequisites($studentCourses, $requiredCourses)
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

    protected function checkYearPrerequisite($student, $course)
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
