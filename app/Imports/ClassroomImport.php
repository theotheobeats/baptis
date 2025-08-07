<?php

namespace App\Imports;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ClassroomImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating classroom data
        $classroom = Classroom::withTrashed()->find($row[0]);
        if ($classroom !== null) {
            $classroom->name = $row[1];
            $classroom->note = $row[2];
            $classroom->save();
        } else {
            $classroom = new Classroom;
            $classroom->name = $row[1];
            $classroom->note = $row[2];
            $classroom->save();
        }

        return $classroom;
    }

    public function startRow(): int
    {
        return 2;
    }
}
