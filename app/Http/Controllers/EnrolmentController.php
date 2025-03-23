<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Prerequisite;
use App\Models\Student;


class EnrolmentController extends Controller
{

    function canEnrollInCourse($studentId, $courseId) {
        // Step 1: Get the student and their enrolled courses
        $student = Student::find($studentId);
    
        if (!$student) {
            return false; // Student not found
        }
    
        // Get the student's completed courses (assumes "enrolled" means completed)
        $completedCourses = $student->courses()
            ->pluck('course_code')
            ->toArray();

        // Step 2: Get the course and its prerequisites
        $course = Course::find($courseId);
    
        if (!$course) {
            return false; // Course not found
        }
    
        $prerequisiteGroups = Prerequisite::where('course_id', 2)->value('prerequisite_groups');// Assuming a one-to-one relationship
    
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
    
    public function testEnrollment()
{
    $result = $this->canEnrollInCourse(1,2);
    if ($result) {
        return 'Student can enroll in the course.'; // Return a string if true
    } else {
        return 'Student cannot enroll in the course.'; // Return a string if false
    }
}
}
