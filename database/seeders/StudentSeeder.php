<?php

namespace Database\Seeders;

use App\EnrolmentStatus;
use App\Models\User;
use App\Models\Student;
use App\Models\Program;
use App\Models\Course;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $program = Program::first();

            if (!$program) {
                $this->command->warn('No programs found. Please run ProgramSeeder first.');
                return;
            }

            $studentsToCreate = [
                [
                    'first_name' => 'Pui',
                    'last_name' => 'Chen',
                    'student_id_prefix' => 'S11209162',
                    'dob' => '2000-03-15',
                    'phone' => '1234567890',
                    'grade' => 3.5,
                ],
                [
                    'first_name' => 'Aryan',
                    'last_name' => 'Sharma',
                    'student_id_prefix' => 'S11210082',
                    'dob' => '2003-04-08',
                    'phone' => '7777777',
                    'grade' => 3.5,
                ],
                [
                    'first_name' => 'Pranav',
                    'last_name' => 'Chand',
                    'student_id_prefix' => 'S11171153',
                    'dob' => '2000-08-04',
                    'phone' => '9034927',
                    'grade' => 3.5,
                ],
            ];

            $firstCourse = Course::first();

            if (!$firstCourse) {
                $this->command->warn('No courses found. Please ensure courses are seeded and have a "cost" attribute.');
                return;
            }

            // Ensure the course has a 'cost' attribute; otherwise, transactions might fail.
            if (!isset($firstCourse->cost)) {
                $this->command->error('The first course does not have a "cost" attribute. Please ensure your Course model and migration include it.');
                return;
            }

            foreach ($studentsToCreate as $studentData) {
                $studentId = $studentData['student_id_prefix'];
                $email = strtolower($studentId) . '@student.usp.ac.fj';
                $fullName = $studentData['first_name'] . ' ' . $studentData['last_name'];

                $user = User::create([
                    'name' => $fullName,
                    'email' => $email,
                    'password' => Hash::make($studentId),
                ]);

                $user->assignRole('student');

                $student = Student::create([
                    'user_id' => $user->id,
                    'student_id' => $studentId,
                    'first_name' => $studentData['first_name'],
                    'last_name' => $studentData['last_name'],
                    'dob' => $studentData['dob'],
                    'email' => $email,
                    'phone' => $studentData['phone'],
                    'program_id' => $program->id,
                    'enrollment_year' => Carbon::now()->year,
                ]);

                $student->courses()->attach($firstCourse->id, [
                    'grade' => $studentData['grade'],
                    'status' => EnrolmentStatus::COMPLETED->value,
                ]);

                // Create a transaction for the student, using the course's cost
                $transaction = Transaction::create([
                    'student_id' => $student->id,
                    'reference_number' => 'TRN-' . strtoupper(Str::random(10)),
                    'amount' => $firstCourse->cost, // Amount derived from course cost
                    'status' => 'completed',
                ]);

                // Attach the course to the transaction
                $transaction->courses()->attach($firstCourse->id);
            }
        });
    }
}
