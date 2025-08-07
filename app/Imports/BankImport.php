<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\Due;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BankImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating due data
        $bank = Bank::withTrashed()->find($row[0]);
        if ($bank !== null) {
            $bank->name = $row[1];
            $bank->pic_name = $row[2];
            $bank->pic_phone = $row[3];
            $bank->save();
        } else {
            $bank = new Bank;
            $bank->pic_name = $row[2];
            $bank->pic_phone = $row[3];
            $bank->save();
        }

        return $bank;
    }

    public function startRow(): int
    {
        return 2;
    }
}
