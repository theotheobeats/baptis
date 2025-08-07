<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Due;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'code' => 'CASH',
                'name' => 'Cash',
                'finance_account_id' => 1
            ],
            [
                'code' => 'MASPION',
                'name' => 'Maspion',
                'finance_account_id' => 4

            ],
            [
                'code' => 'BCA',
                'name' => 'BCA',
                'finance_account_id' => 5
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create([
                'code' => $bank['code'],
                'name' => $bank['name'],
                'finance_account_id' => $bank['finance_account_id']
            ]);
        }
    }
}
