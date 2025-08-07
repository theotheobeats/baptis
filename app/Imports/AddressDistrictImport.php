<?php

namespace App\Imports;

use App\Models\AddressDistrict;
use App\Models\Bank;
use App\Models\Due;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AddressDistrictImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating due data
        $address_district = AddressDistrict::withTrashed()->find($row[0]);
        if ($address_district !== null) {
            $address_district->name = $row[1];
            $address_district->save();
        } else {
            $address_district = new AddressDistrict();
            $address_district->name = $row[1];
            $address_district->save();
        }

        return $address_district;
    }

    public function startRow(): int
    {
        return 2;
    }
}
