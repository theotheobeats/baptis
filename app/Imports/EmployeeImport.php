<?php

namespace App\Imports;

use App\Helpers\DataHelper;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\EmployeeBranch;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmployeeImport implements ToModel, WithStartRow
{
    public function model(array $rows)
    {   
        if($rows[4] !== null) {
            $position = Position::where("name", $rows[4])->first();
            if($position == null) {
                $position = new Position();
                $position->name = $rows[4];
                $position->save();
            }
        }
        
        // Mulai proses input / update data pegawai
        $employee = Employee::withTrashed()->find($rows[0]);
        if ($employee != null) {
            $employee->name = $rows[1];
            $employee->address = $rows[2];
            $employee->phone_number = $rows[3];
            $employee->position_id = $position->id;
            $employee->save();
        } else {
            $employee = new Employee;
            $employee->name = $rows[1];
            $employee->address = $rows[2];
            $employee->phone_number = $rows[3];
            $employee->position_id = $position->id;
            $employee->save();
        }

        return $employee;
    }

    public function startRow(): int
    {
        return 2;
    }
}
