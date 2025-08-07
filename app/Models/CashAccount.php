<?php

namespace App\Models;

use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CashAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "cash_accounts";

    public static function do_open(Request $request)
    {
        try {
            DB::beginTransaction();
            date_default_timezone_set('Asia/Jakarta');

            // Cek ada akun kas terbuka
            $not_close_sales_cash_account = CashAccount::where("employee_id", "=", UserInfoHelper::employee_id())
                ->whereNull("close_time")
                ->get();

            if (count($not_close_sales_cash_account) > 0) {
                return ResponseHelper::response_error("Gagal membuka akun kas penjualan", "Terdapat akun kas penjualan yang masih aktif");
            }

            // Cek belum ada akun kas terbuka atas nama pegawai tersebut
            $opened_sales_account = CashAccount::where("employee_id", "=", UserInfoHelper::employee_id())
                ->where("open_time", env("LIKE"), date("Y-m-d") . "%")
                ->get();

            if (count($opened_sales_account) > 0) {
                return ResponseHelper::response_error("Gagal membuka akun kas penjualan", "Anda sudah melakukan penjualan di hari ini");
            }

            $sales_cash_account = new CashAccount;
            $sales_cash_account->employee_id = UserInfoHelper::employee_id();
            $sales_cash_account->open_time = now();
            $sales_cash_account->beginning_balance = $request->beginning_balance;
            $sales_cash_account->created_by = UserInfoHelper::employee_id();
            $sales_cash_account->save();

            $encrypted_id = Crypt::encrypt($sales_cash_account->id);

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success_transaction("Proses Input Berhasil", "Akun kas telah dibuka", $encrypted_id);
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Gagal", "Akun kas penjualan tidak dapat dibuka! " . $e);
        }
    }

    public static function do_close(Request $request)
    {
        try {
            DB::beginTransaction();

            $close_time = now();

            // Cek akun kas statusnya masih tertutup
            $not_close_sales_cash_account = CashAccount::where("employee_id", "=", UserInfoHelper::employee_id())
                ->whereNull("close_time")
                ->get();

            if (count($not_close_sales_cash_account) == 0) {
                return ResponseHelper::response_error("Gagal menutup akun kas penjualan", "Tidak ada akun kas penjualan yang aktif");
            }

            $sales_cash_account = CashAccount::find($not_close_sales_cash_account[0]->id);
            $sales_cash_account->close_time = now();
            $sales_cash_account->closing_balance = $request->closing_balance;
            $sales_cash_account->expense_balance = $request->expense_balance;

            $sales_cash_account->updated_by = UserInfoHelper::employee_id();
            $sales_cash_account->save();

            $encrypted_id = Crypt::encrypt($sales_cash_account->id);

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success_transaction("Proses Penutupan Kas Berhasil", "Akun kas telah ditutup", $encrypted_id);
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Gagal", "Akun kas penjualan tidak dapat ditutup! " . $e);
        }
    }
}
