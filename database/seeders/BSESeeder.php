<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BSESeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // List of courses with required details
        $courses = [
            ['code' => 'CS111', 'title' => 'Introduction to Computing Science', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS112', 'title' => 'Data Structures & Algorithms', 'year' => 1, 'cost' => 500, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS140', 'title' => 'Introduction to Software Engineering', 'year' => 1, 'cost' => 450, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'MA111', 'title' => 'Calculus 1 & Linear Algebra 1', 'year' => 1, 'cost' => 400, 'semester_1' => true, 'semester_2' => true],
            ['code' => 'MA161', 'title' => 'Discrete Mathematics 1', 'year' => 1, 'cost' => 400, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'MG101', 'title' => 'Introduction to Management', 'year' => 1, 'cost' => 300, 'semester_1' => true, 'semester_2' => true],
            ['code' => 'ST131', 'title' => 'Introduction to Statistics', 'year' => 1, 'cost' => 400, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'UU100A', 'title' => 'Communications & Information Literacy', 'year' => 1, 'cost' => 200, 'semester_1' => true, 'semester_2' => true],
            ['code' => 'UU114', 'title' => 'English Language Skills for Tertiary Studies', 'year' => 1, 'cost' => 200, 'semester_1' => true, 'semester_2' => true],

            ['code' => 'CS211', 'title' => 'Computer Organisation', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS214', 'title' => 'Design & Analysis of Algorithms', 'year' => 2, 'cost' => 550, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS218', 'title' => 'Mobile Computing', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS219', 'title' => 'Cloud Computing', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS230', 'title' => 'Requirements Engineering', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS241', 'title' => 'Software Design & Implementation', 'year' => 2, 'cost' => 550, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'IS221', 'title' => 'Web Applications Development', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'IS222', 'title' => 'Database Managment Systems', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'UU200', 'title' => 'Ethics & Governance', 'year' => 2, 'cost' => 250, 'semester_1' => true, 'semester_2' => true],

            // Year III
            ['code' => 'CS310', 'title' => 'Computer Networks', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS311', 'title' => 'Operating Systems', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS324', 'title' => 'Distributed Computing', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS341', 'title' => 'Software Quality Assurance & Testing', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS352', 'title' => 'Cybersecurity Principles', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'IS314', 'title' => 'Computing Project', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'IS328', 'title' => 'Data Mining', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'IS333', 'title' => 'Project Management', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            
            ['code' => 'CS415', 'title' => 'Artificial Intelligence', 'year' => 4, 'cost' => 700, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS403', 'title' => 'Cyber Defence: Governance & Risk Management', 'year' => 4, 'cost' => 700, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS412', 'title' => 'Artificial Intelligence', 'year' => 4, 'cost' => 750, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS424', 'title' => 'Big Data Technologies', 'year' => 4, 'cost' => 750, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS400', 'title' => 'Industry Experience Project', 'year' => 4, 'cost' => 1000, 'semester_1' => false, 'semester_2' => true],
        ];

        
        // Insert courses and attach to program
        foreach ($courses as $course) {
            $courseId = DB::table('courses')->insertGetId([
                'course_code' => $course['code'],
                'course_title' => $course['title'],
                'year' => $course['year'],
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
                'cost' => $course['cost'],
                'semester_1' => $course['semester_1'],
                'semester_2' => $course['semester_2'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert into pivot table
            DB::table('course_program')->insert([
                'course_id' => $courseId,
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
