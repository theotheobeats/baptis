<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\StudentClassroom;
use Faker\Provider\ar_EG\Address;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppConfigSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
            ClassroomSeeder::class,
            // StudentSeeder::class,
            TeacherSeeder::class,
            // DueSeeder::class,
            // CoaSeeder::class,
            BankSeeder::class,
            SchoolYearSeeder::class,
            // StudentClassroomSeeder::class,
            AccessibilitySeeder::class,
            // DistrictSeeder::class,
            // VillageSeeder::class,
            ReconciliationSeeder::class
        ]);
    }
}
