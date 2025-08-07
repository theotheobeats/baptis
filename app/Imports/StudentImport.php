<?php

namespace App\Imports;

use App\Helpers\DataHelper;
use App\Models\AddressDistrict;
use App\Models\AddressVillage;
use App\Models\Branch;
use App\Models\Classroom;
use App\Models\Employee;
use App\Models\EmployeeBranch;
use App\Models\Position;
use App\Models\Student;
use App\Models\StudentClassroom;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        $village = null;
        $district = null;

        // Input tahun ajaran

        $address1 = $row[24];
        if ($address1 !== null) {
            $village = AddressVillage::where("name", "=", $address1)->first();
            if ($village == null) {
                $village = new AddressVillage();
                $village->name = $address1;
                $village->save();
            }
        }

        $address2 = $row[25];
        if ($address2 !== null) {
            $district = AddressDistrict::where("name", "=", $address2)->first();
            if ($district == null) {
                $district = new AddressDistrict();
                $district->name = $address2;
                $district->save();
            }
        }
        
        $class = $row[27];
        if ($class !== null) {
            $classroom = Classroom::where("name", "=", $class)->first();
            if ($classroom == null) {
                $classroom = new Classroom();
                $classroom->name = $class;
                $classroom->save();
            }
        }
        
        $student = Student::withTrashed()->find($row[0]);
        if ($student == null) {
            $student = new Student();
        }

        $student->nis = $row[1];
        $student->nisn = $row[2];
        $student->name = $row[3];
        $student->gender = $row[4];
        $student->birth_date = $row[5];
        $student->birth_place = $row[6];
        $student->religion = $row[7];
        $student->address = $row[8];
        $student->phone = $row[9];
        $student->father_name = $row[10];
        $student->father_phone = $row[11];
        $student->father_address = $row[12];
        $student->father_religion = $row[13];
        $student->father_job = $row[14];
        $student->mother_name = $row[15];
        $student->mother_phone = $row[16];
        $student->mother_address = $row[17];
        $student->mother_religion = $row[18];
        $student->mother_job = $row[19];
        $student->parent_contact = $row[20];
        $student->backtrack_student_whatsapp_number = $row[21];
        $student->rt = $row[22];
        $student->rw = $row[23];
        // 24
        if ($village != null) {
            $student->village_id = $village->id;
        }
        // 25
        if ($district != null) {
            $student->district_id = $district->id;
        }
        $student->postal_code = $row[26];
        // 27
        if ($classroom != null) {
            $student->backtrack_current_classroom_id = $classroom->id;
            $student->backtrack_current_classroom_name = $classroom->name;
            $student->backtrack_class_grade = DataHelper::get_classroom_grade_va_code($classroom->school_group);
        }

        if ($student->backtrack_student_whatsapp_number == null || $student->backtrack_student_whatsapp_number == "") {
            $student->backtrack_student_whatsapp_number = DataHelper::whatsapp_phone_number_formatter($row[20]);
        }

        // $student->backtrack_student_whatsapp_number = DataHelper::whatsapp_phone_number_formatter($row[20]);
        $student->save();


        // Disable student classroom yang lain
        StudentClassroom::where("student_id", "=", $student->id)->update(["is_active" => 0]);

        // Insert student classroom
        $student_classroom = new StudentClassroom;
        $student_classroom->student_id = $student->id;
        $student_classroom->classroom_id = $classroom->id;
        $student_classroom->school_year_id = 6;
        $student_classroom->is_active = 1;
        $student_classroom->status = StudentClassroom::$STATUS_CLASS_PROMOTION;
        $student_classroom->save();



        return $student;
    }

    public function startRow(): int
    {
        return 2;
    }
}
