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
        $student = Student::create($studentData);


        $courses = Course::take(1)->get();
        // Attach first course with completed status and GPA (grade)
        foreach ($courses as $course) {
            $student->courses()->attach($course->id, [
                'grade' => 3.5,  // Example grade for completed course
                'status' => EnrolmentStatus::COMPLETED->value,  // Example status - completed
            ]);
        }

        // // Attach second course with enrolled status (no grade)
        // $courses = Course::skip(1)->take(1)->get();  // Skip the first course and take the next one
        // foreach ($courses as $course) {
        //     $student->courses()->attach($course->id, [
        //         'grade' => 3.5,  // Example grade for completed course
        //         'status' => EnrolmentStatus::COMPLETED->value,  // Example status - completed
        //     ]);
        // }

        $studentData2 = [
            'user_id' => null,  // Will be set later to the created User ID
            'student_id' => 'S11210082', // Unique student ID
            'first_name' => 'Aryan',
            'last_name' => 'Sharma',
            'dob' => '2003-04-08',  // Date of Birth
            'email' => 's11210082@student.usp.ac.fj',
            'phone' => '7777777',
            'program_id' => 1,  // Optional: assign to an existing program
            'enrollment_year' => Carbon::now()->year,  // Current year
        ];

        // Create the user for this student
        $user2 = User::create([
            'name' => $studentData2['first_name'] . ' ' . $studentData2['last_name'],
            'email' => $studentData2['email'],
            'password' => Hash::make('S11210082'),  // Default password
        ]);


        $user2->assignRole('student');

        // Set the user_id for the student data
        $studentData2['user_id'] = $user2->id;

        // Create the student record
        $student2 = Student::create($studentData2);


        $courses = Course::take(1)->get();
        // Attach courses with pivot data (grade and enrollment status)
        foreach ($courses as $course) {
            $student2->courses()->attach($course->id, [
                'grade' => 3.5,  // Example grade
                'status' => EnrolmentStatus::COMPLETED->value,  // Example status
            ]);
        }





        //USER 3 BELOW


        $studentData3 = [
            'user_id' => null,  // Will be set later to the created User ID
            'student_id' => 'S11171153', // Unique student ID
            'first_name' => 'Pranav',
            'last_name' => 'Chand',
            'dob' => '2000-08-04',  // Date of Birth
            'email' => 's11171153@student.usp.ac.fj',
            'phone' => '9034927',
            'program_id' => 1,  // Optional: assign to an existing program
            'enrollment_year' => Carbon::now()->year,  // Current year
        ];

        // Create the user for this student
        $user3 = User::create([
            'name' => $studentData3['first_name'] . ' ' . $studentData2['last_name'],
            'email' => $studentData3['email'],
            'password' => Hash::make('S11171153'),  // Default password
        ]);


        $user3->assignRole('student');

        // Set the user_id for the student data
        $studentData3['user_id'] = $user3->id;

        // Create the student record
        $student3 = Student::create($studentData3);

        $courses = Course::take(1)->get();
        // Attach courses with pivot data (grade and enrollment status)
        foreach ($courses as $course) {
            $student3->courses()->attach($course->id, [
                'grade' => 3.5,  // Example grade
                'status' => EnrolmentStatus::COMPLETED->value,  // Example status
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
