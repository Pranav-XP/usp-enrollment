<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

         // Inserting a Bachelors of Software Engineering (BSE) program record
         DB::table('programs')->insert([
            'program_code' => 'BSE',
            'name' => 'Bachelors of Software Engineering',
            'description' => 'A program focused on developing software engineering skills.',
            'duration' => 4, // Example: 4 years
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Inserting a Bachelors of Software Engineering (BSE) program record
        DB::table('programs')->insert([
            'program_code' => 'BNS',
            'name' => 'Bachelor of Networks & Security',
            'description' => 'A program focused on developing networking and security skills.',
            'duration' => 4, // Example: 4 years
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->call(RoleSeeder::class);
        $this->call(BSESeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(PrerequisiteSeeder::class);

    }
}
