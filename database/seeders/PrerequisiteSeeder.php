<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PrerequisiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prerequisites')->insert([
            'course_id' => 2,
            'prerequisite_groups' => json_encode(["CS111"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 10,
            'prerequisite_groups' => json_encode(["CS111"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 11,
            'prerequisite_groups' => json_encode(["CS112"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 12,
            'prerequisite_groups' => json_encode(["CS112"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 13,
            'prerequisite_groups' => json_encode(["CS112"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 14,
            'prerequisite_groups' => json_encode(["CS111"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 15,
            'prerequisite_groups' => json_encode(["CS112","CS230"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 16,
            'prerequisite_groups' => json_encode([["CS111","IS122","IS104"]]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 17,
            'prerequisite_groups' => json_encode([["CS111","IS122","IS104"]]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 18,
            'prerequisite_groups' => json_encode(["UU100A","UU114"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 19,
            'prerequisite_groups' => json_encode(["CS211"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 20,
            'prerequisite_groups' => json_encode(["CS211"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 21,
            'prerequisite_groups' => json_encode([["CS218","CS219","CS214","CS215"]]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 22,
            'prerequisite_groups' => json_encode(["CS241"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 24,
            'prerequisite_groups' => json_encode(["IS222",["CS241","CS214","IS226"]]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 25,
            'prerequisite_groups' => json_encode(["IS222"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 30,
            'prerequisite_groups' => json_encode(["CS324"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        //BNS PREREQUISITES
        DB::table('prerequisites')->insert([
            'course_id' => 33,
            'prerequisite_groups' => json_encode(["CS111","CS150"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 34,
            'prerequisite_groups' => json_encode(["CS215"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 35,
            'prerequisite_groups' => json_encode(["CS215"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 36,
            'prerequisite_groups' => json_encode(["CS310"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('prerequisites')->insert([
            'course_id' => 30,
            'prerequisite_groups' => json_encode(["CS324"]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
