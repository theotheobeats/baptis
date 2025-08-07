<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeBranch;
use App\Models\EmployeeWarehouse;
use App\Models\Student;
use App\Models\WarehouseBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                "name" => "Christopher Alberto",
                "nis" => "9032",
                "nisn" => "12342345483",
                "gender" => "Male",
                "birth_date" => "2004/02/21",
                "birth_place" => "Palembang",
                "religion" => "Kristen",
                "parent_name" => "Budi Amin",
                "parent_phone" => "081231456789",
                "parent_religion" => "Kristen",
                "parent_address" => "Jl. Sapta Marga, No. 1922A",
                "backtrack_current_classroom_id" => 1,
                "backtrack_current_classroom_name" => "1A",
            ],
            [
                "name" => "Yolanda Chouw",
                "nis" => "9034",
                "nisn" => "14234248883",
                "gender" => "Female",
                "birth_date" => "2004/02/21",
                "birth_place" => "Palembang",
                "religion" => "Kristen",
                "parent_name" => "Tje Fuk Fong",
                "parent_phone" => "081231456789",
                "parent_religion" => "Kristen",
                "parent_address" => "Jl. Jendral Sudirman, No. 1922A",
                "backtrack_current_classroom_id" => 2,
                "backtrack_current_classroom_name" => "1B",
            ],
            [
                "name" => "Matthew Koh",
                "nis" => "9035",
                "nisn" => "1112248433",
                "gender" => "Male",
                "birth_date" => "2004/02/21",
                "birth_place" => "Palembang",
                "religion" => "Kristen",
                "parent_name" => "Asun Koh",
                "parent_phone" => "081231456789",
                "parent_religion" => "Kristen",
                "parent_address" => "Jl. Sapta Marga, No. 1922A",
                "backtrack_current_classroom_id" => 3,
                "backtrack_current_classroom_name" => "1C",
            ],


        ];


        // foreach ($students as $student) {
        //     Student::create([
        //         'name' => $student['name'],
        //         'nis' => $student['nis'],
        //         'nisn' => $student['nisn'],
        //         'gender' => $student['gender'],
        //         'birth_date' => $student['birth_date'],
        //         'birth_place' => $student['birth_place'],
        //         'religion' => $student['religion'],
        //         'parent_name' => $student['parent_name'],
        //         'parent_phone' => $student['parent_phone'],
        //         'parent_religion' => $student['parent_religion'],
        //         'parent_address' => $student['parent_address'],
        //         'backtrack_current_classroom_id' => $student['backtrack_current_classroom_id'],
        //         'backtrack_current_classroom_name' => $student['backtrack_current_classroom_name'],
        //     ]);
        // }
    }
}
