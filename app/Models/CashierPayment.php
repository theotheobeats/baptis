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

class CashierPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "cashier_payments";

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            $count = count(CashierPayment::where("created_at", "LIKE", date("Y-m-d") . "%")->select("id")->get()) + 1;
            $code = "CP-" . date("ymd") . "-" . str_pad($count, 6, "0", STR_PAD_LEFT);

            // Proses Input data
            $cashier_payment = new CashierPayment();
            $cashier_payment->date = $request->date;
            $cashier_payment->student_id = $request->student_id;
            $cashier_payment->amount = $request->amount;
            $cashier_payment->coa_1_id = $request->coa_1_id;
            $cashier_payment->coa_1_debit = $request->coa_1_debit ?? 0;
            $cashier_payment->coa_1_credit = $request->coa_1_credit ?? 0;
            $cashier_payment->coa_2_id = $request->coa_2_id ?? null;
            $cashier_payment->coa_2_debit = $request->coa_2_debit ?? 0;
            $cashier_payment->coa_2_credit = $request->coa_2_credit ?? 0;
            $cashier_payment->amount = DataHelper::get_raw_number($request->amount);
            $cashier_payment->note = $request->note ?? "";

            $cashier_payment->created_by = UserInfoHelper::employee_id();
            $cashier_payment->save();

            FinanceHelper::add_cashflow($cashier_payment->coa_1_id, $cashier_payment->coa_1_debit, $cashier_payment->coa_1_credit, $code, $code, "Pembayaran Kasir " . $code . " - " . $cashier_payment->note, UserInfoHelper::employee_id(), $cashier_payment->date);

            if ($cashier_payment->coa_2_id != null) {
                FinanceHelper::add_cashflow($cashier_payment->coa_2_id, $cashier_payment->coa_2_debit, $cashier_payment->coa_2_credit, $code, $code, "Pembayaran Kasir " . $code . " - " . $cashier_payment->note, UserInfoHelper::employee_id(), $cashier_payment->date);
            }

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e->getMessage());
        }
    }


    public static function do_update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $cashier_payment = CashierPayment::find($id);

            // Cek data ada di database
            if ($cashier_payment == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $cashier_payment->note = $request->note ?? "";
            $cashier_payment->updated_by = UserInfoHelper::employee_id();
            $cashier_payment->save();

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
            $cashier_payment = CashierPayment::find($id);

            // Cek data ada di database
            if ($cashier_payment == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $cashier_payment->deleted_by = UserInfoHelper::employee_id();
            $cashier_payment->deleted_at = now();
            $cashier_payment->save();

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
