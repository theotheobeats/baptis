<?php

namespace App\Imports;

use App\Models\Due;
use App\Models\Student;
use App\Models\StudentDue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentDueImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        $dues = Due::get();
        $student = Student::withTrashed()->where("nis", "=", $row[1])->first();
        if ($student != null) {

            $row_number = 3;
            foreach ($dues as $due) {
                $price = $row[$row_number];
                $student_due = StudentDue::where("student_id", "=", $student->id)
                    ->where("due_id", "=", $due->id)
                    ->first();

                if ($student_due) {
                    if ($price == 0) {
                        $student_due->delete();
                    } else {
                        $student_due->price = $price;
                        $student_due->save();
                    }
                } else {
                    if ($price != 0) {
                        $student_due = new StudentDue;
                        $student_due->student_id = $student->id;
                        $student_due->due_id = $due->id;
                        $student_due->price = $price;
                        $student_due->save();
                    }
                }

                $row_number++;
            }

            return $student;
        }
    }


    public function startRow(): int
    {
        return 2; // Assuming the data starts from the second row (header row is not included)
    }
}
