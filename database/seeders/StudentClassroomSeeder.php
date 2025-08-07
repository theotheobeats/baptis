<?php

namespace Database\Seeders;

use App\Models\StudentClassroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $student_classrooms = [
            [
                "student_id" => 1,
                "classroom_id" => 1,
                "school_year_id" => 6
            ],
            [
                "student_id" => 2,
                "classroom_id" => 2,
                "school_year_id" => 6
            ],
            [
                "student_id" => 3,
                "classroom_id" => 3,
                "school_year_id" => 6
            ],
        ];

        foreach ($student_classrooms as $sc) {
            StudentClassroom::create([
                'student_id' => $sc['student_id'],
                'classroom_id' => $sc['classroom_id'],
                'school_year_id' => $sc['school_year_id'],
                'is_active' => 1,
            ]);
        }
    }
}
