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

class Bank extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "banks";

    public const MASPION_BANK_ID = 2;
    public const BCA_BANK_ID = 3;

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $bank = new Bank();
            $bank->code = $request->code;
            $bank->name = $request->name;
            $bank->pic_name = $request->pic_name;
            $bank->pic_phone = $request->pic_phone;
            $bank->finance_account_id = $request->finance_account_id;
            $bank->created_by = UserInfoHelper::employee_id();
            $bank->save();

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
            $bank = Bank::find($id);

            // Cek data ada di database
            if ($bank == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $bank->code = $request->code;
            $bank->name = $request->name;
            $bank->pic_name = $request->pic_name;
            $bank->pic_phone = $request->pic_phone;
            $bank->finance_account_id = $request->finance_account_id;
            $bank->updated_by = UserInfoHelper::employee_id();
            $bank->save();

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
            $bank = Bank::find($id);

            // Cek data ada di database
            if ($bank == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $bank->deleted_by = UserInfoHelper::employee_id();
            $bank->deleted_at = now();
            $bank->save();

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
