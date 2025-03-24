<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BNSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            //YEAR 1
            //1['code' => 'CS111', 'title' => 'Introduction to Computing Science', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false],
            //2['code' => 'CS112', 'title' => 'Programming Fundamentals', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS150', 'title' => 'Introduction to Computer Networks & Security', 'year' => 1, 'cost' => 500, 'semester_1' => false, 'semester_2' => true],
            //4['code' => 'MA111', 'title' => 'Mathematics for Computing', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false],
            //5['code' => 'MA161', 'title' => 'Discrete Mathematics', 'year' => 1, 'cost' => 500, 'semester_1' => false, 'semester_2' => true],
            //6['code' => 'MG101', 'title' => 'Introduction to Management', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false],
            //7['code' => 'ST131', 'title' => 'Introduction to Statistics', 'year' => 1, 'cost' => 500, 'semester_1' => false, 'semester_2' => true],
            //8['code' => 'UU100A', 'title' => 'Communication and Information Literacy', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false],
            //9['code' => 'UU114', 'title' => 'English for Academic Purposes', 'year' => 1, 'cost' => 500, 'semester_1' => false, 'semester_2' => true],
            
            //YEAR 2
            //10['code' => 'CS211', 'title' => 'Data Structures and Algorithms', 'year' => 2, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            //11['code' => 'CS214', 'title' => 'Algorithm Analysis', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS215', 'title' => 'Computer Communications & Management', 'year' => 2, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            //12['code' => 'CS218', 'title' => 'Database Management Systems', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            //13['code' => 'CS219', 'title' => 'Operating Systems', 'year' => 2, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            //16['code' => 'IS221', 'title' => 'Information Systems Analysis', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            //17['code' => 'IS222', 'title' => 'Enterprise Information Systems', 'year' => 2, 'cost' => 600, 'semester_1' => true, 'semester_2' => false],
            //18['code' => 'UU200', 'title' => 'Ethics and Governance', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true],
            
            //YEAR 3
            //19['code' => 'CS310', 'title' => 'Advanced Software Engineering', 'year' => 3, 'cost' => 700, 'semester_1' => false, 'semester_2' => true],
            //20['code' => 'CS311', 'title' => 'Artificial Intelligence', 'year' => 3, 'cost' => 700, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS317', 'title' => 'Computer & Network Security', 'year' => 3, 'cost' => 700, 'semester_1' => false, 'semester_2' => true],
            //21['code' => 'CS324', 'title' => 'Machine Learning', 'year' => 3, 'cost' => 700, 'semester_1' => true, 'semester_2' => false],
            ['code' => 'CS350', 'title' => 'Wireless Networks', 'year' => 3, 'cost' => 700, 'semester_1' => false, 'semester_2' => true],
            ['code' => 'CS351', 'title' => 'Network Design & Administration', 'year' => 3, 'cost' => 700, 'semester_1' => true, 'semester_2' => false],
            //23['code' => 'CS352', 'title' => 'Software Testing', 'year' => 3, 'cost' => 700, 'semester_1' => false, 'semester_2' => true],
            //26['code' => 'IS333', 'title' => 'IT Project Management', 'year' => 3, 'cost' => 700, 'semester_1' => true, 'semester_2' => false],
            
            //YEAR 4
            //28['code' => 'CS403', 'title' => 'Big Data Analytics', 'year' => 4, 'cost' => 800, 'semester_1' => false, 'semester_2' => true],
            //29['code' => 'CS412', 'title' => 'Blockchain Technology', 'year' => 4, 'cost' => 800, 'semester_1' => true, 'semester_2' => false],
            //30['code' => 'CS424', 'title' => 'Software Architecture', 'year' => 4, 'cost' => 800, 'semester_1' => false, 'semester_2' => true],
            //31['code' => 'CS400', 'title' => 'Capstone Project', 'year' => 4, 'cost' => 800, 'semester_1' => true, 'semester_2' => false],
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
                'program_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            }

            $matchingCourseIds = [1,2,4,5,6,7,8,9,10,11,
    12,13,16,17,18,19,20,21,23,26,28,29,30,31];

        $insertData = [];

        foreach ($matchingCourseIds as $courseId) {
            $insertData[] = [
                'course_id' => $courseId,
                'program_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert into pivot table
        DB::table('course_program')->insert($insertData);

    }
}
