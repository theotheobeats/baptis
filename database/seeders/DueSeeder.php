<?php

namespace Database\Seeders;

use App\Models\Due; 
use Illuminate\Database\Seeder;

class DueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dues = [
            [
                'name' => 'SPP',
                'price' => 250000,
                'can_cancel' => 0,
            ],
            [
                'name' => 'Ekskul Robotik',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Ekskul Futsal',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Ekskul Karate',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Ekskul Tari',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Ekskul English Club',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Ekskul Coloring',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Ekskul Modern Dance',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Les Komputer',
                'price' => 75000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Les Tematik',
                'price' => 125000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Les Bahasa Inggris',
                'price' => 45000,
                'can_cancel' => 1,
            ],
            [
                'name' => 'Les Bahasa Mandarin',
                'price' => 45000,
                'can_cancel' => 1,
            ],
        ];

        foreach ($dues as $due) {
            Due::create([
                'name' => $due['name'],
                'price' => $due['price'],
                'can_cancel' => $due['can_cancel'],
            ]);
        }
    }
}
