<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schoolyears = [
            ['name' => '2021/2022'],
            ['name' => '2022/2023'],
            ['name' => '2023/2024'],
            ['name' => '2024/2025'],
        ];

        $semester = ['Ganjil', 'Genap'];

        foreach ($schoolyears as $sy) {
            foreach ($semester as $s) {
                if ($sy['name'] == '2023/2024' && $s == 'Genap') {
                    SchoolYear::create([
                        'name' => $sy['name'],
                        'semester' => $s,
                        'is_active' => 1
                    ]);
                } else {
                    SchoolYear::create([
                        'name' => $sy['name'],
                        'semester' => $s,
                        'is_active' => 0
                    ]);
                }
            }
        }
    }
}
