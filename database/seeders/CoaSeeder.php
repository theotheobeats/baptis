<?php

namespace Database\Seeders;

use App\Models\FinanceAccount;
use Illuminate\Database\Seeder;

class CoaSeeder extends Seeder
{
    public function run()
    {
        $coas = [
            ['code' => '1.01.01', 'name' => 'Kas Besar'],
            ['code' => '1.01.02', 'name' => 'Kas Kecil'],
            ['code' => '4.01.01', 'name' => 'Pendapatan Iuran SPP'],
            ['code' => '1.01.03', 'name' => "Maspion"],
            ['code' => '1.01.04', 'name' => "BCA"],
        ];

        foreach ($coas as $coa) {
            FinanceAccount::create([
                'code' => $coa['code'],
                'name' => $coa['name'],
            ]);
        }
    }
}
