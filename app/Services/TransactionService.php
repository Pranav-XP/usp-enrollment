<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Course;
use App\Models\Semester; // Still needed if the service internally uses Semester, but not for parameter
use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionService
{
    /**
     * Creates a new transaction and connects it to the student, course(s), and semester.
     *
     * @param Student  $student The student associated with the transaction.
     * @param Course|array<Course>|int|array<int> $courses The course(s) associated with the transaction. Can be a single Course model, an array of Course models, a single course ID, or an array of course IDs.
     * @param int $semesterId The ID of the semester associated with the transaction. // CHANGED TYPE HINT
     * @param float|null $amount Optional. The amount of the transaction. If null, it will try to sum costs from provided courses.
     * @param string $status Optional. The status of the transaction (default: 'pending').
     * @param string|null $referenceNumber Optional. A custom reference number. If null, a UUID will be generated.
     * @return Transaction The newly created transaction model.
     * @throws \InvalidArgumentException If course input is invalid or amount cannot be determined.
     */
    public function createEnrollmentTransaction(
        Student $student,
        Course|array $courses,
        int $semesterId,
        ?float $amount = null,
        string $status = 'pending',
        ?string $referenceNumber = null
    ): Transaction {
        // Ensure courses is an array of IDs
        if (!is_array($courses)) {
            $courses = [$courses]; // Convert single Course/ID to array
        }

        $courseIds = [];
        $totalCourseCost = 0;

        foreach ($courses as $courseItem) {
            $courseModel = null;
            if ($courseItem instanceof Course) {
                $courseModel = $courseItem;
            } elseif (is_int($courseItem) || is_string($courseItem)) { // Handle integer or string IDs
                $courseModel = Course::find($courseItem);
            }

            if (!$courseModel) {
                throw new \InvalidArgumentException("Invalid course provided. Could not find Course model for ID: " . (is_object($courseItem) ? $courseItem->id : $courseItem));
            }

            $courseIds[] = $courseModel->id;
            $totalCourseCost += $courseModel->cost;
        }

        // Determine the transaction amount
        $finalAmount = $amount ?? $totalCourseCost;

        if ($finalAmount <= 0) {
            throw new \InvalidArgumentException("Transaction amount must be greater than zero. Please provide a valid amount or ensure courses have costs.");
        }

        // Create the transaction record
        $transaction = Transaction::create([
            'student_id'       => $student->id,
            'semester_id'      => $semesterId,
            'reference_number' => 'TRN-' . strtoupper(Str::random(10)),
            'amount'           => $finalAmount,
            'status'           => $status,
        ]);

        // Attach the course(s) to the transaction via the pivot table
        // This handles attaching multiple courses if they are provided as an array
        $transaction->courses()->attach($courseIds);

        return $transaction;
    }
}
