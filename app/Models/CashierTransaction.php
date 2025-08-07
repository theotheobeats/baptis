<?php

namespace App\Models;

use App\Helpers\DataHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\HasTrackHistory;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "cashier_transactions";

    // public static function do_store(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $qty = count(CashierTransaction::where("created_at", "LIKE", date("Y-m-d") . "%")->select("id")->get()) + 1;
    //         $code = "CT-" . date("ymd") . "-" . str_pad($qty, 6, "0", STR_PAD_LEFT);

    //         // Proses Input data
    //         $cashier_transaction = new CashierTransaction();
    //         $cashier_transaction->transaction_date = $request->transaction_date;
    //         $cashier_transaction->transaction_type = $request->transaction_type;
    //         $cashier_transaction->student_id = $request->student_id;
    //         $cashier_transaction->bank_id = $request->bank_id;
    //         $cashier_transaction->account_id = $request->account_id;
    //         $cashier_transaction->amount = DataHelper::get_raw_number($request->amount);
    //         $cashier_transaction->note = $request->note ?? "";

    //         $cashier_transaction->created_by = UserInfoHelper::employee_id();
    //         $cashier_transaction->save();

    //         FinanceHelper::add_cashflow($cashier_transaction->account_id, 0, $cashier_transaction->amount, $code, $code, "Penjualan Kasir " . $code . " - " . $cashier_transaction->note, UserInfoHelper::employee_id(), $cashier_transaction->transaction_date);

    //         // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
    //         DB::commit();
    //         return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
    //     } catch (Exception $e) {
    //         // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
    //         DB::rollBack();
    //         return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e->getMessage());
    //     }
    // }


    // public static function do_update($id, Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Proses Input data
    //         $cashier_transaction = CashierTransaction::find($id);

    //         // Cek data ada di database
    //         if ($cashier_transaction == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

    //         // Jika ada, lanjut update data
    //         $cashier_transaction->transaction_date = $request->transaction_date;
    //         $cashier_transaction->bank_id = $request->bank_id;
    //         $cashier_transaction->note = $request->note ?? "";
    //         $cashier_transaction->updated_by = UserInfoHelper::employee_id();
    //         $cashier_transaction->save();

    //         // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
    //         DB::commit();
    //         return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
    //     } catch (Exception $e) {
    //         // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
    //         DB::rollBack();
    //         return ResponseHelper::response_error("Proses Ubah Gagal", "Proses input data gagal!");
    //     }
    // }


    // public static function do_delete($id)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Proses Input data
    //         $cashier_transaction = CashierTransaction::find($id);

    //         // Cek data ada di database
    //         if ($cashier_transaction == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

    //         // Jika ada, input data yang hapus
    //         $cashier_transaction->deleted_by = UserInfoHelper::employee_id();
    //         $cashier_transaction->deleted_at = now();
    //         $cashier_transaction->save();

    //         // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
    //         DB::commit();
    //         return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
    //     } catch (Exception $e) {
    //         // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
    //         DB::rollBack();
    //         return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
    //     }
    // }
}
