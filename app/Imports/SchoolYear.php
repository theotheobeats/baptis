<?php

namespace App\Imports;

use App\Models\Due;
use App\Models\Position;
use App\Models\SchoolYear;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PositionImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating due data
        $school_year = SchoolYear::withTrashed()->find($row[0]);
        if ($school_year !== null) {
            $school_year->name = $row[1];
            $school_year->semester = $row[2];
            if ($row[2] == "aktif") {
                $school_year->is_active = true;
            } else {
                $school_year->is_active = false;
            }
            $school_year->save();
        } else {
            $school_year = new SchoolYear();
            $school_year->name = $row[1];
            $school_year->semester = $row[2];
            if ($row[2] == "aktif") {
                $school_year->is_active = 1;
            } else {
                $school_year->is_active = 0;
            }
            $school_year->save();
        }

        return $school_year;
    }

    public function startRow(): int
    {
        return 2;
    }
}
