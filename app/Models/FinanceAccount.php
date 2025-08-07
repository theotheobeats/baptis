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

class FinanceAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "finance_accounts";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $finance_account = new FinanceAccount();
            $finance_account->code = $request->code;
            $finance_account->name = $request->name;
            $finance_account->sub_detail = $request->sub_detail;
            $finance_account->description = $request->description;
            $finance_account->backtrack_current_credit = $request->backtrack_current_credit;
            $finance_account->backtrack_current_debit = $request->backtrack_current_debit;
            $finance_account->display_for_cashier = $request->display_for_cashier == "yes";
            $finance_account->hide_coa = $request->hide_coa == "yes";
            $finance_account->created_by = UserInfoHelper::employee_id();
            $finance_account->save();

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
            $finance_account = FinanceAccount::find($id);

            // Cek data ada di database
            if ($finance_account == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $finance_account->code = $request->code;
            $finance_account->name = $request->name;
            $finance_account->sub_detail = $request->sub_detail;
            $finance_account->description = $request->description;
            $finance_account->backtrack_current_credit = $request->backtrack_current_credit;
            $finance_account->backtrack_current_debit = $request->backtrack_current_debit;
            $finance_account->display_for_cashier = $request->display_for_cashier == "yes";
            $finance_account->hide_coa = $request->hide_coa == "yes";
            $finance_account->updated_by = UserInfoHelper::employee_id();
            $finance_account->save();

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
            $finance_account = FinanceAccount::find($id);

            // Cek data ada di database
            if ($finance_account == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $finance_account->deleted_by = UserInfoHelper::employee_id();
            $finance_account->deleted_at = now();
            $finance_account->save();

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
