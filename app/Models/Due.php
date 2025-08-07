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

class Due extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "dues";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $due = new Due();
            $due->name = $request->name;
            $due->price = $request->price;
            $due->finance_account_id = $request->finance_account_id;
            $due->can_cancel = 1;
            $due->is_active = 1;
            $due->created_by = UserInfoHelper::employee_id();
            $due->save();

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
            $due = Due::find($id);

            // Cek data ada di database
            if ($due == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $due->name = $request->name;
            $due->price = $request->price;
            $due->finance_account_id = $request->finance_account_id;
            $due->updated_by = UserInfoHelper::employee_id();
            $due->save();

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
            $due = Due::find($id);

            // Cek data ada di database
            if ($due == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $due->deleted_by = UserInfoHelper::employee_id();
            $due->deleted_at = now();
            $due->save();

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
