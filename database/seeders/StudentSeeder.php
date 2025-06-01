<?php

namespace Database\Seeders;

use App\EnrolmentStatus;
use App\Models\User;
use App\Models\Student;
use App\Models\Program;
use App\Models\Course;
use App\Models\Transaction;
use App\Models\Semester; // Import the Semester model
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
            // Get the first program. Ensure ProgramSeeder runs first.
            $program = Program::first();
            if (!$program) {
                $this->command->warn('No programs found. Please run ProgramSeeder first.');
                return;
            }

            // Important: We still need activeSemester for other general checks/setup if needed
            // but for course-specific transactions, we'll find the *correct* semester.
            $activeSemester = Semester::getActiveSemester();
            if (!$activeSemester) {
                // If there's no active semester, maybe just warn, but continue if other parts don't strictly need it
                // For this seeder, we'll find specific semesters, so an active one isn't strictly necessary for the transaction logic itself.
                $this->command->warn('No active semester found. This might affect general enrollment checks in other parts of the app.');
            }


            // Get the first course. Ensure CourseSeeder runs first.
            $firstCourse = Course::first();
            if (!$firstCourse) {
                $this->command->warn('No courses found. Please ensure courses are seeded.');
                return;
            }

            // Verify the course has a 'cost' attribute for transactions.
            if (!isset($firstCourse->cost)) {
                $this->command->error('The first course does not have a "cost" attribute. Please ensure your Course model and migration include it.');
                return;
            }

            // Define student data as an array of arrays to reduce repetition.
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

            foreach ($studentsToCreate as $studentData) {
                $studentId = $studentData['student_id_prefix'];
                $email = strtolower($studentId) . '@student.usp.ac.fj';
                $fullName = $studentData['first_name'] . ' ' . $studentData['last_name'];

                // Create the user for the student.
                $user = User::create([
                    'name' => $fullName,
                    'email' => $email,
                    'password' => Hash::make($studentId),
                ]);
                $user->assignRole('student');

                // Set the enrollment year for the student (e.g., current year)
                // For 'completed' courses, this would ideally be the year they completed it.
                // For seeding purposes, we'll use the current year.
                $studentEnrollmentYear = Carbon::now()->year;

                // Create the student record.
                $student = Student::create([
                    'user_id' => $user->id,
                    'student_id' => $studentId,
                    'first_name' => $studentData['first_name'],
                    'last_name' => $studentData['last_name'],
                    'dob' => $studentData['dob'],
                    'email' => $email,
                    'phone' => $studentData['phone'],
                    'program_id' => $program->id,
                    'enrollment_year' => $studentEnrollmentYear,
                ]);

                // --- NEW LOGIC: Determine the correct semester ID for the course's offering ---
                $courseSemesterId = null;

                // Prioritize Semester 1 if the course is offered in it
                if ($firstCourse->semester_1) {
                    $semester1 = Semester::where('year', $studentEnrollmentYear)
                        ->where('term', 1)
                        ->first();
                    if ($semester1) {
                        $courseSemesterId = $semester1->id;
                    } else {
                        $this->command->warn("Could not find Semester 1 for year {$studentEnrollmentYear} for course {$firstCourse->course_code}.");
                    }
                }

                // If Semester 1 was not applicable/found, try Semester 2
                // This 'else if' or 'if (!$courseSemesterId && ...)' ensures we only try Semester 2 if Semester 1 wasn't chosen.
                if (is_null($courseSemesterId) && $firstCourse->semester_2) {
                    $semester2 = Semester::where('year', $studentEnrollmentYear)
                        ->where('term', 2)
                        ->first();
                    if ($semester2) {
                        $courseSemesterId = $semester2->id;
                    } else {
                        $this->command->warn("Could not find Semester 2 for year {$studentEnrollmentYear} for course {$firstCourse->course_code}.");
                    }
                }

                // If no suitable semester could be found for the course, skip this enrollment/transaction
                if (is_null($courseSemesterId)) {
                    $this->command->error(
                        "Skipping enrollment and transaction for student {$student->student_id} " .
                            "as course {$firstCourse->course_code} is not offered in either Semester 1 or 2 " .
                            "for the enrollment year {$studentEnrollmentYear}. " .
                            "Please ensure corresponding semesters are seeded."
                    );
                    continue; // Skip to the next student
                }


                // Attach the course to the student with grade, status, and the determined courseSemesterId.
                $student->courses()->attach($firstCourse->id, [
                    'grade' => $studentData['grade'],
                    'status' => EnrolmentStatus::COMPLETED->value,
                    'semester_id' => $courseSemesterId, // Use the determined ID
                ]);

                // Create a transaction for the student, using the course's cost and the determined courseSemesterId.
                $transaction = Transaction::create([
                    'student_id' => $student->id,
                    'reference_number' => 'TRN-' . strtoupper(Str::random(10)),
                    'amount' => $firstCourse->cost,
                    'status' => 'completed',
                    'semester_id' => $courseSemesterId, // Use the determined ID
                ]);

                // Attach the course to the transaction.
                $transaction->courses()->attach($firstCourse->id);
            }
        });
    }
}
