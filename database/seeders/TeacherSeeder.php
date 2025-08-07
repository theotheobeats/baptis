<?php

namespace Database\Seeders;

use App\Models\Guru; // Assuming 'Guru' is the Indonesian term for Teacher
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $teachers = [
        //     [
        //         "name" => "Budi Santoso",
        //         "phone" => "081234567890",
        //     ],
        //     [
        //         "name" => "Dewi Susanti",
        //         "phone" => "087654321098",
        //     ],
        //     [
        //         "name" => "Adi Nugroho",
        //         "phone" => "085678945612",
        //     ],
        //     [
        //         "name" => "Citra Wijaya",
        //         "phone" => "089876543210",
        //     ],
        //     [
        //         "name" => "Eka Putri",
        //         "phone" => "081234509876",
        //     ],
        // ];

        // foreach ($teachers as $guru) {
        //     Teacher::create([
        //         'name' => $guru['name'],
        //         'phone' => $guru['phone'],
        //     ]);
        // }
    }
}
