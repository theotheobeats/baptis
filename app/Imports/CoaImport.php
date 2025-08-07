<?php

namespace App\Imports;

use App\Models\FinanceAccount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CoaImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating finance account data
        if ($row[1] == "" || $row[1] == null || $row[2] == "" || $row[2] == null) {
            return null;
        }
        $finance_account = FinanceAccount::withTrashed()->find($row[0]);
        if ($finance_account !== null) {
            $finance_account->code = $row[1];
            $finance_account->name = $row[2];
            $finance_account->description = $row[3];
            $finance_account->sub_detail = $row[4];
            $finance_account->display_for_cashier = $row[4] == "Ya" ? 1 : 0;
            $finance_account->save();
        } else {
            $finance_account = new FinanceAccount;
            $finance_account->code = $row[1];
            $finance_account->name = $row[2];
            $finance_account->description = $row[3];
            $finance_account->sub_detail = $row[4];
            $finance_account->display_for_cashier = $row[4] == "Ya" ? 1 : 0;
            $finance_account->save();
        }

        return $finance_account;
    }

    public function startRow(): int
    {
        return 2;
    }
}

