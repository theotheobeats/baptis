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

class InvoiceDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "invoice_details";

    public static function do_update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $invoice_detail = InvoiceDetail::find($id);
            if ($invoice_detail == null) return ResponseHelper::response_error("Proses Ubah Tagihan Gagal", "Data tidak ditemukan");

            if($invoice_detail->payed_amount > 0) return ResponseHelper::response_error("Proses Ubah Tagihan Gagal", "Tagihan sudah dibayarkan");


            // Ambil nilai tagihan sebelum perubahan
            $old_price = DataHelper::get_raw_number($invoice_detail->price);
            $new_price = DataHelper::get_raw_number($request->price);

            $invoice_detail->price = $new_price;
            $invoice_detail->save();

            // Update nilai tertagih di invoice header tagihan
            $invoice = Invoice::find($invoice_detail->invoice_id);

            // Reset harga tagihan terlebih dahulu
            $invoice->price = $invoice->price - $old_price;
            $invoice->bill_price = $invoice->bill_price - $old_price;

            $invoice->price = $invoice->bill_price + $new_price;
            $invoice->bill_price = $invoice->bill_price + $new_price;

            $invoice->save();


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Ubah Jumlah Tagihan Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }

    public static function do_destroy($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $invoice_detail = InvoiceDetail::find($id);
            if ($invoice_detail == null) return ResponseHelper::response_error("Proses Hapus Tagihan Gagal", "Data tidak ditemukan");

            if($invoice_detail->payed_amount > 0) return ResponseHelper::response_error("Proses Hapus Tagihan Gagal", "Tagihan sudah dibayarkan");

            $invoice = Invoice::where("id", $invoice_detail->invoice_id)->first();

            // Ambil nilai tagihan 
            $invoice_detail_price = DataHelper::get_raw_number($invoice_detail->price);

            // Update nilai tagihan
            $invoice->price = $invoice->price - $invoice_detail_price;
            $invoice->bill_price = $invoice->bill_price - $invoice_detail_price;
            $invoice->save();

            // Update data tagihan telah dihapus
            $invoice_detail->deleted_by = UserInfoHelper::employee_id();
            $invoice_detail->deleted_at = now();
            $invoice_detail->save();
            

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Jumlah Tagihan Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }
}
