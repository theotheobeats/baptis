<?php

namespace App\Imports;

use App\Models\Due;
use App\Models\Position;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PositionImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating due data
        $position = Position::withTrashed()->find($row[0]);
        if ($position !== null) {
            $position->name = $row[1];
            $position->description = $row[2];
            $position->save();
        } else {
            $position = new Position();
            $position->name = $row[1];
            $position->description = $row[2];
            $position->save();
        }

        return $position;
    }

    public function startRow(): int
    {
        return 2;
    }
}
