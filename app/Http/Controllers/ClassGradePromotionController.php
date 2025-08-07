<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Helpers\UserInfoHelper;
use App\Helpers\ResponseHelper;
use App\Models\StudentClassroom;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentClassroomImport;
use Illuminate\Support\Facades\DB;

class ClassGradePromotionController extends Controller
{
    public static $information = [
        "title" => "Kenaikan Kelas",
        "route" => "/class-management/class-grade-promotion",
        "view" => "pages.class-managements.grade-promotion."
    ];

    // ======================
    // Line View - START
    // ======================

    public function index()
    {
        if (!UserInfoHelper::has_access("class_grade_promotion", "view")) return abort(404);
        $school_year = SchoolYear::where('is_active', 1)->firstOrFail();

        return view(self::$information["view"] . "index", [
            "information" => self::$information,
            "school_year" => $school_year
        ]);
    }

    // ======================
    // Line Proses - START
    // ======================

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("class_grade_promotion", "export")) return abort(404);
        $next_school_year_id = $request->school_year_id;
        $current_school_year_id = $request->current_school_year_id;

        $student_classrooms = new StudentClassroom;
        $student_classrooms = $student_classrooms->join('students', 'students.id', '=', 'student_classrooms.student_id');
        $student_classrooms = $student_classrooms->join('classrooms', 'classrooms.id', '=', 'student_classrooms.classroom_id');
        $student_classrooms = $student_classrooms->where('school_year_id', $current_school_year_id);
        $student_classrooms = $student_classrooms->where('student_classrooms.is_active', 1);
        $student_classrooms = $student_classrooms->whereNull('students.non_active_at'); // Hanya siswa yang aktif
        $student_classrooms = $student_classrooms->select(
            "students.id as student_id",
            "students.nis as student_nis",
            "students.name as student_name",
            "classrooms.name as current_classroom_name",
            "school_year_id"
        );
        $student_classrooms = $student_classrooms->orderBy('students.id', 'asc');
        $student_classrooms = $student_classrooms->get();

        $current_school_year = SchoolYear::find($current_school_year_id);
        $next_school_year = SchoolYear::find($next_school_year_id);

        $result[] = [
            ["text" => "No"],
            ["text" => "ID*"],
            ["text" => "NIS"],
            ["text" => "Nama Siswa"],
            ["text" => "Nama Kelas Sekarang"],
            ["text" => "Tahun Ajaran Sekarang"],
            ["text" => "Semester Sekarang"],
            ["text" => "Nama Kelas Baru"],
            ["text" => "Tahun Ajaran Baru"],
            ["text" => "Semester Baru"]
        ];

        $i = 1;
        foreach ($student_classrooms as $sc) {
            $result[] = [
                ["text" => $i++],
                ["text" => $sc->student_id],
                ["text" => $sc->student_nis],
                ["text" => $sc->student_name],
                ["text" => $sc->current_classroom_name],
                ["text" => $current_school_year->name],
                ["text" => $current_school_year->semester],
                ["text" => ""],
                ["text" => $next_school_year->name],
                ["text" => $next_school_year->semester]
            ];
        }

        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("class_grade_promotion", "import")) return abort(404);
        try {
            DB::beginTransaction();

            // Set semua data kelas siswa menjadi tidak aktif
            $student_classrooms = new StudentClassroom;
            $student_classrooms = $student_classrooms->where('is_active', 1);
            $student_classrooms = $student_classrooms->update(['is_active' => 0]);

            Excel::import(new StudentClassroomImport, request()->file('file-excel'));
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            $result = ResponseHelper::response_error('Gagal', 'Data gagal diimport. ' . $e->getMessage());
            return response()->json($result['client_response'], $result['code']);
        }
        $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
        return response()->json($result['client_response'], $result['code']);
    }
}
