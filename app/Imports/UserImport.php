<?php

namespace App\Imports;

use App\Helpers\DataHelper;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UserImport implements ToModel, WithStartRow
{
    public function model(array $rows)
    {

        $employee_id = 1;
        if ($rows[4] != null) {
            $employee = Employee::where("name", "LIKE", $rows[4])->first();
            if ($employee == null) {
                $employee = new Employee();
                $employee->name = $rows[4];
                $employee->address = "";
                $employee->phone_number = "";
                $employee->position_id = 1;
                $employee->photo = "default.jpg";
                $employee->selected_branch_id = 1;
                $employee->save();
            }
            $employee_id = $employee->id;
        }


        // Mulai proses input / update data pegawai
        $user = User::withTrashed()->find($rows[0]);
        if ($user != null) {
            $user->email = $rows[1];
            $user->username = $rows[2];
            if ($rows[3] != null || $rows[3] != '') {
                $user->password = bcrypt($rows[3]);
            }
            $user->employee_id = $employee_id;
            $user->save();
        } else {
            $user = new User;
            $user->email = $rows[1];
            $user->username = $rows[2];
            $user->password = bcrypt($rows[3]);
            $user->employee_id = $employee_id;
            $user->save();
        }

        return $user;
    }

    public function startRow(): int
    {
        return 2;
    }
}
