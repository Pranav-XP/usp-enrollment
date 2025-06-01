<?php

namespace Database\Seeders;

use App\Models\Semester; // Import the Semester model
use Illuminate\Database\Seeder;
use Carbon\Carbon; // For date manipulation
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing semesters to avoid duplicates on re-seeding
        Semester::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the semesters to be created
        $semesters = [
            [
                'year' => 2024,
                'term' => 1,
                'name' => '2024 Semester 1',
                'start_date' => '2024-02-05',
                'end_date' => '2024-06-14',
                'is_active' => false, // Not active anymore
            ],
            [
                'year' => 2024,
                'term' => 2,
                'name' => '2024 Semester 2',
                'start_date' => '2024-07-22',
                'end_date' => '2024-11-29',
                'is_active' => false, // Not active anymore
            ],
            [
                'year' => 2025,
                'term' => 1,
                'name' => '2025 Semester 1',
                'start_date' => '2025-02-03',
                'end_date' => '2025-06-13',
                'is_active' => false, // This will be the currently active semester
            ],
            [
                'year' => 2025,
                'term' => 2,
                'name' => '2025 Semester 2',
                'start_date' => '2025-07-21',
                'end_date' => '2025-11-28',
                'is_active' => true,
            ],
            [
                'year' => 2026,
                'term' => 1,
                'name' => '2026 Semester 1',
                'start_date' => '2026-02-02',
                'end_date' => '2026-06-12',
                'is_active' => false,
            ],
        ];

        // Insert the semester data
        foreach ($semesters as $semesterData) {
            Semester::create($semesterData);
        }

        $this->command->info('Semesters seeded successfully!');
    }
}
