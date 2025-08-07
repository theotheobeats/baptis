<?php

namespace App\Imports;

use App\Models\AddressVillage;
use App\Models\Bank;
use App\Models\Due;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AddressVillageImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating due data
        $address_village = AddressVillage::withTrashed()->find($row[0]);
        if ($address_village !== null) {
            $address_village->name = $row[1];
            $address_village->save();
        } else {
            $address_village = new AddressVillage();
            $address_village->name = $row[1];
            $address_village->save();
        }

        return $address_village;
    }

    public function startRow(): int
    {
        return 2;
    }
}
