<?php

namespace Database\Seeders;

use App\EnrolmentStatus;
use App\Models\User;
use App\Models\Student;
use App\Models\Program;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Create mock program data (this assumes you already have programs in the database)
        // If you don't, create them first using a separate seeder
        $program = Program::first();  // Assuming a program already exists in the 'programs' table

        // Example student data
        $studentData = [
            'user_id' => null,  // Will be set later to the created User ID
            'student_id' => 'S11209162', // Unique student ID
            'first_name' => 'Pui',
            'last_name' => 'Chen',
            'dob' => '2000-03-15',  // Date of Birth
            'email' => 'S11209162@student.usp.ac.fj',
            'phone' => '1234567890',
            'program_id' => $program ? $program->id : null,  // Optional: assign to an existing program
            'enrollment_year' => Carbon::now()->year,  // Current year
        ];

        // Create the user for this student
        $user = User::create([
            'name' => $studentData['first_name'] . ' ' . $studentData['last_name'],
            'email' => $studentData['email'],
            'password' => Hash::make('S11209162'),  // Default password
        ]);

       
        $user->assignRole('student');

        // Set the user_id for the student data
        $studentData['user_id'] = $user->id;

        // Create the student record
        $student=Student::create($studentData);

         
         $courses = Course::take(1)->get(); 
         // Attach courses with pivot data (grade and enrollment status)
         foreach ($courses as $course) {
             $student->courses()->attach($course->id, [
                 'grade' => 3.5,  // Example grade
                 'status' => EnrolmentStatus::ENROLLED->value,  // Example status
             ]);
         }

         
/*       //To test CS324
         $cloud_computing = Course::find(13);

         $student->courses()->attach($cloud_computing->id,[
            'grade'=>3.0,
            'status'=>EnrolmentStatus::ENROLLED->value,
         ]); */
    }
}
