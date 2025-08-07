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

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "students";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            $classroom = Classroom::find($request->classroom_id);

            // Proses Input data
            $student = new Student();
            $student->nis = $request->nis;
            $student->nisn = $request->nisn;
            $student->name = $request->name;
            $student->gender = $request->gender;
            $student->birth_date = $request->birth_date;
            $student->birth_place = $request->birth_place;
            $student->religion = $request->religion;
            $student->address = $request->address;
            $student->phone = $request->phone;
            // $student->parent_name = $request->parent_name;
            // $student->parent_phone = $request->parent_phone;
            // $student->parent_address = $request->parent_address;
            // $student->parent_religion = $request->parent_religion;
            $student->rt = $request->rt;
            $student->rw = $request->rw;
            $student->postal_code = $request->postal_code;
            $student->village_id = $request->village_id;
            $student->district_id = $request->district_id;

            $student->father_name = $request->father_name;
            $student->father_phone = $request->father_phone;
            $student->father_address = $request->father_address;
            $student->father_religion = $request->father_religion;
            $student->father_job = $request->father_job;

            $student->mother_name = $request->mother_name;
            $student->mother_phone = $request->mother_phone;
            $student->mother_address = $request->mother_address;
            $student->mother_religion = $request->mother_religion;
            $student->mother_job = $request->mother_job;

            $student->backtrack_current_classroom_id = $classroom->id;
            $student->backtrack_current_classroom_name = $classroom->name;
            $student->backtrack_class_grade = DataHelper::get_classroom_grade_va_code($classroom->school_group);

            $student->parent_contact = $request->parent_contact;
            $student->backtrack_student_whatsapp_number = $request->backtrack_student_whatsapp_number;

            $student->created_by = UserInfoHelper::employee_id();
            $student->save();

            $student_clasroom = new StudentClassroom();
            $student_clasroom->student_id = $student->id;
            $student_clasroom->classroom_id = $classroom->id;
            $student_clasroom->school_year_id = $request->school_year_id;
            $student_clasroom->is_active = 1;
            $student_clasroom->created_by = UserInfoHelper::employee_id();
            $student_clasroom->save();

            // if ($request->va_number) {
            //     $student_va = new StudentVaAccount();
            //     $student_va->student_id = $student->id;
            //     $student_va->bank_id = $request->bank_id;
            //     $student_va->va_number = $request->va_number;
            //     $student_va->created_by = UserInfoHelper::employee_id();
            //     $student_va->save();
            // }

            // if (isset($request->due_id_list)) {
            //     for ($i = 0; $i < count($request->due_id_list); $i++) {
            //         $student_due = new StudentDue();
            //         $student_due->student_id = $student->id;
            //         $student_due->due_id = $request->due_id_list[$i];
            //         $student_due->price = $request->due_price_list[$i];
            //         $student_due->save();
            //     }
            // }

            if (isset($request->va_number_list)) {
                for ($i = 0; $i < count($request->va_number_list); $i++) {
                    $student_va = new StudentVaAccount();
                    $student_va->student_id = $student->id;
                    $student_va->bank_id = $request->bank_id_list[$i];
                    $student_va->va_number = $request->va_number_list[$i];
                    $student_va->created_by = UserInfoHelper::employee_id();
                    $student_va->save();
                }
            }

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }


    public static function do_update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $student = Student::find($id);

            // Cek data ada di database
            if ($student == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");


            // $classroom = Classroom::find($request->classroom_id);

            // Jika ada, lanjut update data
            $student->nis = $request->nis;
            $student->nisn = $request->nisn;
            $student->name = $request->name;
            $student->gender = $request->gender;
            $student->birth_date = $request->birth_date;
            $student->birth_place = $request->birth_place;
            $student->religion = $request->religion;
            $student->address = $request->address;
            $student->phone = $request->phone;
            // $student->parent_name = $request->parent_name;
            // $student->parent_phone = $request->parent_phone;
            // $student->parent_address = $request->parent_address;
            // $student->parent_religion = $request->parent_religion;
            $student->rt = $request->rt;
            $student->rw = $request->rw;
            $student->postal_code = $request->postal_code;
            $student->village_id = $request->village_id;
            $student->district_id = $request->district_id;

            $student->father_name = $request->father_name;
            $student->father_phone = $request->father_phone;
            $student->father_address = $request->father_address;
            $student->father_religion = $request->father_religion;
            $student->father_job = $request->father_job;

            $student->mother_name = $request->mother_name;
            $student->mother_phone = $request->mother_phone;
            $student->mother_address = $request->mother_address;
            $student->mother_religion = $request->mother_religion;
            $student->mother_job = $request->mother_job;

            $student->parent_contact = $request->parent_contact;
            $student->backtrack_student_whatsapp_number = $request->backtrack_student_whatsapp_number;

            // $student->backtrack_current_classroom_id = $classroom->id;
            // $student->backtrack_current_classroom_name = $classroom->name;

            $student->updated_by = UserInfoHelper::employee_id();
            $student->save();

            // StudentDue::where("student_id", $student->id)->forceDelete();

            // if (isset($request->due_id_list)) {
            //     for ($i = 0; $i < count($request->due_id_list); $i++) {
            //         $student_due = new StudentDue();
            //         $student_due->student_id = $student->id;
            //         $student_due->due_id = $request->due_id_list[$i];
            //         $student_due->price = $request->due_price_list[$i];
            //         $student_due->save();
            //     }
            // }

            if (isset($request->va_number_list)) {

                for ($i = 0; $i < count($request->va_number_list); $i++) {
                    $student_va = StudentVaAccount::where("student_id", $student->id)
                        ->where("bank_id", $request->bank_id_list[$i])
                        ->first();
                    if ($student_va != null){
                        $student_va->bank_id = $request->bank_id_list[$i];
                        $student_va->va_number = $request->va_number_list[$i];
                        $student_va->updated_by = UserInfoHelper::employee_id();
                        $student_va->save();
                    }else{
                        $student_va = new StudentVaAccount();
                        $student_va->student_id = $student->id;
                        $student_va->bank_id = $request->bank_id_list[$i];
                        $student_va->va_number = $request->va_number_list[$i];
                        $student_va->created_by = UserInfoHelper::employee_id();
                        $student_va->save();
                    }
                }
            }


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Ubah Gagal", "Proses input data gagal!");
        }
    }


    public static function do_delete($id)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $student = Student::find($id);


            // Cek data ada di database
            if ($student == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            StudentVaAccount::where("student_id", "=", $student->id)->forceDelete();

            // Jika ada, input data yang hapus
            $student->deleted_by = UserInfoHelper::employee_id();
            $student->deleted_at = now();
            $student->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
        }
    }

    public static function do_student_due_bind_store($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $student = Student::find($id);
            if ($student == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data siswa tidak ditemukan");

            $due_id = $request->due_id;
            $student_due = StudentDue::where("student_id", "=", $student->id)
                ->where("due_id", "=", $due_id)->first();

            if ($student_due) return ResponseHelper::response_error("Proses Tambah Iuran Gagal", "Iuran sudah terdaftar");

            $student_due = new StudentDue;
            $student_due->student_id = $student->id;
            $student_due->due_id = $due_id;

            $student_due->created_by = UserInfoHelper::employee_id();
            $student_due->save();


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data iuran telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!");
        }
    }

    public static function do_student_activate($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $student = Student::find($id);


            // Cek data ada di database
            if ($student == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            $student->non_active_at = null;
            $student->non_active_by = null;
            $student->updated_by = UserInfoHelper::employee_id();
            $student->save();


            // handle history
            $student_active_history = new StudentActiveHistory();
            $student_active_history->student_id = $student->id;
            $student_active_history->active_at = now();
            $student_active_history->actived_by = UserInfoHelper::employee_id();
            $student_active_history->note = $request->note;

            $student_active_history->created_by = UserInfoHelper::employee_id();
            $student_active_history->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Pengaktifan Siswa Berhasil", "Siswa telah diaktifkan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Pengaktifan Gagal", "Proses pengaktifan siswa gagal!");
        }
    }

    public static function do_student_deactivate($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $student = Student::find($id);


            // Cek data ada di database
            if ($student == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            $student->non_active_at = now();
            $student->non_active_by = UserInfoHelper::employee_id();
            $student->updated_by = UserInfoHelper::employee_id();
            $student->save();


            // handle history
            $student_active_history = new StudentActiveHistory();
            $student_active_history->student_id = $student->id;
            $student_active_history->non_active_at = now();
            $student_active_history->non_actived_by = UserInfoHelper::employee_id();
            $student_active_history->note = $request->note;

            $student_active_history->created_by = UserInfoHelper::employee_id();
            $student_active_history->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Penonaktifan Siswa Berhasil", "Siswa telah dinonaktifkan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Penonaktifan Gagal", "Proses penonaktifan siswa gagal!" . $e);
        }
    }
}
