<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            ['name' => 'Admin'],
            ['name' => 'Kepala Yayasan'],
            ['name' => 'Kasir'],
            ['name' => 'Accounting'],
            ['name' => 'Head Accounting'],
            ['name' => 'Finance'],
        ];

        foreach ($positions as $position) {
            Position::create([
                'name' => $position['name'],
            ]);
        }
    }
}
