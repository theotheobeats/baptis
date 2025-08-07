<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use App\Helpers\ResponseHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentDue extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "student_dues";


    public static function subscribe(Request $request)
    {
        try {
            DB::beginTransaction();
            $student_id_list = $request->student_id;
            $due_id = $request->due_id;

            $due = Due::find($due_id);
            $due_price = $due->price;

            foreach ($student_id_list as $student_id) {
                $student_due = StudentDue::where("student_id", "=", $student_id)
                    ->where("due_id", "=", $due_id)
                    ->first();

                if ($student_due == null) {
                    $student_due = new StudentDue;
                    $student_due->due_id = $due_id;
                    $student_due->student_id = $student_id;
                    $student_due->price = $due_price;
                    $student_due->save();
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


    public static function unsubscribe(Request $request)
    {
        try {
            DB::beginTransaction();
            $student_id_list = $request->student_id;
            $due_id = $request->due_id;

            $due = Due::find($due_id);
            if (!$due->can_cancel) {
                DB::rollBack();
                return ResponseHelper::response_error("Berhenti Iuran Gagal", "Iuran bersifat wajib diikuti!");
            }

            // Hapus data iuran siswa
            StudentDue::whereIn("student_id", $student_id_list)
                ->where("due_id", "=", $due_id)
                ->delete();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }





    public static function single_subscribe($id, Request $request)
    {
    }


    public static function single_unsubscribe($id)
    {
        try {
            DB::beginTransaction();
            $student_due_id = $id;

            $student_due = StudentDue::find($student_due_id);

            $student_id = $student_due->student_id;
            $due_id = $student_due->due_id;

            $due = Due::find($due_id);
            if (!$due->can_cancel) {
                DB::rollBack();
                return ResponseHelper::response_error("Berhenti Iuran Gagal", "Iuran bersifat wajib diikuti!");
            }

            // Hapus data iuran siswa
            StudentDue::where("id", "=", $student_due_id)->delete();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Berhasil", "Siswa telah berhenti mengikuti kursus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Gagal", "Terjadi kesalahan saat memproses permintaan!" . $e);
        }
    }

    public static function student_due_price_change($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $student_due_id = $id;

            $student_due = StudentDue::find($student_due_id);
            
            $student_due->price = $request->price;
            $student_due->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Berhasil", "Nilai tagihan telah diubah");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Gagal", "Terjadi kesalahan saat memproses permintaan!" . $e);
        }
    }
}
