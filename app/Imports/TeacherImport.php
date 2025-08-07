<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeacherImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Start the process of inputting or updating teacher data
        $teacher = Teacher::withTrashed()->find($row[0]);
        if ($teacher !== null) {
            $teacher->name = $row[1];
            $teacher->phone = $row[2];
            $teacher->save();
        } else {
            $teacher = new Teacher;
            $teacher->name = $row[1];
            $teacher->phone = $row[2];
            $teacher->save();
        }

        return $teacher;
    }

    public function startRow(): int
    {
        return 2;
    }
}
