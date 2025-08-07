<?php

namespace App\Models;

use App\Helpers\DataHelper;
use App\Helpers\HasTrackHistory;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentClassroom extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "student_classrooms";

    public static $STATUS_CLASS_CHANGE = 'Pindah Kelas';
    public static $STATUS_CLASS_PROMOTION = 'Naik Kelas';

    public static function do_change_classroom(Request $request)
    {
        try {
            DB::beginTransaction();

            // Carikan data siswa yang akan dipindahkan kelasnya
            $student_classroom = new StudentClassroom();
            $student_classroom = $student_classroom->where('student_id', $request->student_id);
            $student_classroom = $student_classroom->where('is_active', 1);
            $student_classroom = $student_classroom->first();
            // Ubah status kelas sebelumnya menjadi tidak aktif
            $student_classroom->is_active = 0;
            $student_classroom->save();

            // Proses Input data
            $student_classroom = new StudentClassroom();
            $student_classroom->student_id = $request->student_id;
            $student_classroom->classroom_id = $request->classroom_id;
            $student_classroom->school_year_id = $request->school_year_id;
            $student_classroom->is_active = 1;
            $student_classroom->status = self::$STATUS_CLASS_CHANGE;
            $student_classroom->save();

            $classroom = Classroom::find($request->classroom_id);

            // Update data kelas siswa
            $student = Student::find($request->student_id);
            $student->backtrack_current_classroom_id = $classroom->id;
            $student->backtrack_current_classroom_name = $classroom->name;
            $student->backtrack_class_grade = DataHelper::get_classroom_grade_va_code($classroom->school_group);
            $student->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!");
        }
    }
}
