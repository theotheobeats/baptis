<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolYear extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "school_years";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $school_year = new SchoolYear();
            $school_year->name = $request->name;
            $school_year->semester = $request->semester;
            $school_year->is_active = $request->is_active;
            $school_year->created_by = UserInfoHelper::employee_id();
            $school_year->save();

            // Jika SchoolYear diaktifkan, maka yang lainnya dinonaktifkan
            if ($request->is_active == 1) {
                SchoolYear::where("id", "!=", $school_year->id)->update(["is_active" => 0]);
            }

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!");
        }
    }


    public static function do_update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $school_year = SchoolYear::find($id);

            // Cek data ada di database
            if ($school_year == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika SchoolYear diaktifkan, maka yang lainnya dinonaktifkan
            if ($request->is_active == 1) {
                SchoolYear::where("id", "!=", $id)->update(["is_active" => 0]);
            }

            // Jika ada, lanjut update data
            $school_year->name = $request->name;
            $school_year->semester = $request->semester;
            $school_year->is_active = $request->is_active;
            $school_year->updated_by = UserInfoHelper::employee_id();
            $school_year->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Ubah Gagal", "Proses input data gagal!" . $e->getMessage());
        }
    }


    public static function do_delete($id)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $school_year = SchoolYear::find($id);

            // Cek data ada di database
            if ($school_year == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $school_year->deleted_by = UserInfoHelper::employee_id();
            $school_year->deleted_at = now();
            $school_year->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
        }
    }
}
