<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeBranch;
use App\Models\EmployeeWarehouse;
use App\Models\WarehouseBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                "name" => "Superadmin",
                "address" => "Jl. Jend. Sudirman",
                "phone_number" => "0812 3456 7890",
                "position_id" => 1,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            [
                "name" => "Haidi Sohar",
                "address" => "Jl. Bambu Kuning",
                "phone_number" => "0812 3456 7890",
                "position_id" => 2,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            [
                "name" => "Yuni",
                "address" => "Jl. Jend. Sudirman",
                "phone_number" => "0812 3456 7890",
                "position_id" => 3,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            [
                "name" => "Anggi",
                "address" => "Jl. Jend. Sudirman",
                "phone_number" => "0812 3456 7890",
                "position_id" => 3,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            [
                "name" => "Dewi",
                "address" => "Jl. Jend. Sudirman",
                "phone_number" => "0812 3456 7890",
                "position_id" => 3,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            [
                "name" => "Hunaria",
                "address" => "Jl. Jend. Sudirman",
                "phone_number" => "0812 3456 7890",
                "position_id" => 3,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            [
                "name" => "Purdianto",
                "address" => "Jl. Jend. Sudirman",
                "phone_number" => "0812 3456 7890",
                "position_id" => 3,
                "photo" => "default.jpg",
                "created_by" => 1
            ],
            
        ];


        foreach ($employees as $employee) {
            Employee::create([
                'name' => $employee['name'],
                'address' => $employee['address'],
                'phone_number' => $employee['phone_number'],
                'position_id' => $employee['position_id'],
                'photo' => $employee['photo'],
                'created_by' => $employee['created_by'],
            ]);
        };
    }
}
