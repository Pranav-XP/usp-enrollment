<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\EnrolmentStatus;

class AdminController extends Controller
{
      // Show list of students
      public function showStudentsList()
      {
          $students = Student::all();
          return view('admin.students', compact('students'));
      }
  
      // Show grade form for a student and a course
      public function showGradeForm($studentId)
      {
           // Fetch the student and their enrolled courses with the pivot table data (e.g. grade, status)
            $student = Student::findOrFail($studentId);
            // Fetch enrolled courses
            $enrolledCourses = $student->courses()->get();
    
            return view('admin.update-grade', compact('student', 'enrolledCourses'));
      }
  
      public function updateGrades(Request $request, $studentId)
        {
            $student = Student::findOrFail($studentId);
            $grades = $request->input('grades'); // Grading input from form

            foreach ($grades as $courseId => $grade) {
                $student->courses()->updateExistingPivot($courseId, [
                    'grade' => $grade,
                    'status' => EnrolmentStatus::COMPLETED->value, // Change the status to completed
                ]);
            }

            return redirect()->route('admin.students')->with('success', 'Grades updated successfully');
        }
}
