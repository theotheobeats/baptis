<?php

namespace App\Imports;

use App\Models\Due;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DueImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating due data
        $due = Due::withTrashed()->find($row[0]);
        if ($due !== null) {
            $due->name = $row[1];
            $due->price = $row[2];
            $due->finance_account_id = $row[3];
            $due->save();
        } else {
            $due = new Due;
            $due->name = $row[1];
            $due->price = $row[2];
            $due->finance_account_id = $row[3];
            $due->save();
        }

        return $due;
    }

    public function startRow(): int
    {
        return 2;
    }
}
