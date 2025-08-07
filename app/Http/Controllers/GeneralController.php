<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\DataHelper;
use App\Models\AddressDistrict;
use App\Models\AddressVillage;
use App\Models\Bank;
use App\Models\Classroom;
use App\Models\Due;
use App\Models\Employee;
use App\Models\FinanceAccount;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class GeneralController extends Controller
{
    public function search_district(Request $request)
    {
        $results = [];
        $address_districts = [];
        // if (trim($request->data) != "") {
        $address_districts = new AddressDistrict();
        $address_districts = $address_districts->where("address_districts.name", "LIKE", "%" . $request->data . "%");
        $address_districts = $address_districts->limit(10);
        $address_districts = $address_districts->get();
        // }

        foreach ($address_districts as $ad) {
            $results[] = [
                "id" => $ad->id,
                "text" => $ad->name
            ];
        }
        return response()->json($results);
    }

    public function search_village(Request $request)
    {
        $results = [];
        $address_villages = [];
        // if (trim($request->data) != "") {
        $address_villages = new AddressVillage();
        $address_villages = $address_villages->where("address_villages.name", "LIKE", "%" . $request->data . "%");
        $address_villages = $address_villages->limit(10);
        $address_villages = $address_villages->get();
        // }

        foreach ($address_villages as $av) {
            $results[] = [
                "id" => $av->id,
                "text" => $av->name
            ];
        }
        return response()->json($results);
    }

    public function search_student(Request $request)
    {
        $results = [];
        $students = [];
        // if (trim($request->data) != "") {
        $students = new Student();
        $students = $students->where("students.name", "LIKE", "%" . $request->data . "%");
        $students = $students->orWhere("students.nis", "LIKE", "%" . $request->data . "%");
        $students = $students->limit(10);
        $students = $students->get();
        // }

        foreach ($students as $student) {
            $results[] = [
                "id" => $student->id,
                "text" => $student->nis . "-" . $student->name . " - " . $student->backtrack_current_classroom
            ];
        }
        return response()->json($results);
    }

    public function search_classroom(Request $request)
    {
        $results = [];
        $classrooms = [];
        // if (trim($request->data) != "") {
        $classrooms = new Classroom();
        $classrooms = $classrooms->where("classrooms.name", "LIKE", "%" . $request->data . "%");
        $classrooms = $classrooms->limit(10);
        $classrooms = $classrooms->get();
        // }

        foreach ($classrooms as $classroom) {
            $results[] = [
                "id" => $classroom->id,
                "text" => $classroom->name
            ];
        }
        return response()->json($results);
    }

    public function search_school_year(Request $request)
    {
        $results = [];
        $school_years = [];
        // if (trim($request->data) != "") {
        $school_years = new SchoolYear();
        $school_years = $school_years->where("school_years.name", "LIKE", "%" . $request->data . "%")
            ->orWhere("school_years.semester", "LIKE", "%" . $request->data . "%");
        $school_years = $school_years->limit(10);
        $school_years = $school_years->get();
        // }

        foreach ($school_years as $school_year) {
            $results[] = [
                "id" => $school_year->id,
                "text" => $school_year->semester . " " . $school_year->name . " " . ($school_year->is_active ? "(Aktif)" : "")
            ];
        }
        return response()->json($results);
    }

    public function search_due(Request $request)
    {
        $results = [];
        $dues = [];
        // if (trim($request->data) != "") {
        $dues = new Due();
        $dues = $dues->where("dues.name", "LIKE", "%" . $request->data . "%");
        $dues = $dues->limit(10);
        $dues = $dues->get();
        // }

        foreach ($dues as $due) {
            $results[] = [
                "id" => $due->id,
                "text" => $due->name
            ];
        }
        return response()->json($results);
    }

    public function search_finance_account(Request $request)
    {
        $results = [];
        $finance_accounts = [];
        // if (trim($request->data) != "") {
        $finance_accounts = new FinanceAccount();
        $finance_accounts = $finance_accounts->where("finance_accounts.code", "LIKE", "%" . $request->data . "%");
        $finance_accounts = $finance_accounts->orWhere("finance_accounts.name", "LIKE", "%" . $request->data . "%");
        $finance_accounts = $finance_accounts->limit(15);
        $finance_accounts = $finance_accounts->get();
        // }

        foreach ($finance_accounts as $finance_account) {
            $results[] = [
                "id" => $finance_account->id,
                "text" => $finance_account->code . " - " . $finance_account->name
            ];
        }
        return response()->json($results);
    }

    public function search_bank(Request $request)
    {
        $results = [];
        $banks = [];
        // if (trim($request->data) != "") {
        $banks = new Bank();
        $banks = $banks->where("banks.name", "LIKE", "%" . $request->data . "%");
        $banks = $banks->limit(10);
        $banks = $banks->get();
        // }

        foreach ($banks as $bank) {
            $results[] = [
                "id" => $bank->id,
                "text" => $bank->name
            ];
        }
        return response()->json($results);
    }

    public function search_employee(Request $request)
    {
        $results = [];
        $employees = [];
        // if (trim($request->data) != "") {
        $employees = new Employee();
        $employees = $employees->where("employees.name", "LIKE", "%" . $request->data . "%");
        $employees = $employees->limit(10);
        $employees = $employees->get();
        // }

        foreach ($employees as $employee) {
            $results[] = [
                "id" => $employee->id,
                "text" => $employee->name
            ];
        }
        return response()->json($results);
    }

    public function get_student_current_classroom(Request $request)
    {
        $student = new StudentClassroom;
        $student = $student->join("students", "students.id", "=", "student_classrooms.student_id");
        $student = $student->where("student_id", $request->student_id);
        $student = $student->orderBy("id", "desc");
        $student = $student->select(
            "student_classrooms.id",
            "student_classrooms.student_id",
            "student_classrooms.classroom_id",
            "students.nis as student_nis",
            "students.name as student_name",
        );
        $student = $student->first();

        $classroom = new Classroom;
        $classroom = $classroom->where("id", $student->classroom_id);
        $classroom = $classroom->first();

        return response()->json([
            "student" => $student,
            "classroom" => $classroom
        ]);
    }







    public function search_active_student(Request $request)
    {
        $results = [];
        $students = [];
        // if (trim($request->data) != "") {
        $students = new Student();
        // $students = $students->where("students.name", "LIKE", "%" . $request->q . "%");
        // $students = $students->where("students.name", "LIKE", "%" . $request->q . "%");
        $students = $students->where(function ($row) use ($request) {
            $row->where("students.name", "LIKE", "%" . $request->data . "%")
                ->orWhere("students.nis", "LIKE", "%" . $request->data . "%");
        });
        $students = $students->whereNull("non_active_at");
        $students = $students->limit(10);
        $students = $students->get();
        // }

        foreach ($students as $student) {
            $results[] = [
                "id" => $student->id,
                "encrypted_id" => Crypt::encrypt($student->id),
                "text" => $student->nis . "-" . $student->name . " - " . $student->backtrack_current_classroom
            ];
        }
        return response()->json($results);
    }

}
