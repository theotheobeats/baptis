<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Student;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Helpers\DataHelper;
use App\Helpers\UserInfoHelper;
use App\Models\AddressVillage;
use App\Models\EmployeeBranch;
use App\Models\AddressDistrict;
use App\Models\StudentClassroom;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentClassroomImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Model Excel yang diimport
        // 0 - Nomor
        // 1 - ID Siswa
        // 2 - NIS
        // 3 - Nama Siswa
        // 4 - Nama Kelas Sekarang
        // 5 - Tahun Ajaran Sekarang
        // 6 - Semester Sekarang
        // 7 - Nama Kelas Baru
        // 8 - Tahun Ajaran Baru
        // 9 - Semester Baru

        if (empty($row[7])) {
            // Munculkan error nama kelas tidak lengkap
            // dd($row[3]);
        } else {
            $next_school_year = new SchoolYear;
            $next_school_year = $next_school_year->where('name', $row[8]);
            $next_school_year = $next_school_year->where('semester', $row[9]);
            $next_school_year = $next_school_year->firstOrFail();

            $next_class = new Classroom;
            $next_class = $next_class->where('name', $row[7])->firstOrFail();

            $student_classroom = new StudentClassroom;
            $student_classroom->student_id = $row[1];
            $student_classroom->classroom_id = $next_class->id;
            $student_classroom->teacher_id = null;
            $student_classroom->school_year_id = $next_school_year->id;
            $student_classroom->is_active = 1;
            $student_classroom->status = StudentClassroom::$STATUS_CLASS_PROMOTION;
            $student_classroom->created_by = UserInfoHelper::employee_id();
            $student_classroom->save();

            // Update data siswa
            $student = Student::find($row[1]);
            $student->backtrack_current_classroom_id = $next_class->id;
            $student->backtrack_current_classroom_name = $next_class->name;
            $student->backtrack_class_grade = DataHelper::get_classroom_grade_va_code($next_class->school_group);
            $student->save();
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
