<?php

namespace App\Models;

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

class PaymentRefund extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "payment_refunds";

    public static function do_store(Request $request)
    {
        // Kurangi jumlah sudah dibayar di invoice besar beserta yang ditagih
        // Input kedalam list refund

        try {
            DB::beginTransaction();

            $student_id = $request->student_id;
            $bank_id = $request->bank_id;
            $note = $request->note;

            $month_payment_refund_amount = PaymentRefund::withTrashed()->where("created_at", "LIKE", date("Y-m") . "%")->select("id")->count();
            $refund_code = "PR-" . date("ymd") . "-" . str_pad($month_payment_refund_amount + 1, 4, "0", STR_PAD_LEFT);
            
            // Cek sudah ada refund sebelumnya
            $payment_refund = new PaymentRefund;
            $payment_refund->code = $refund_code;
            $payment_refund->student_id = $student_id;
            $payment_refund->bank_id = $bank_id;
            $payment_refund->note = $note ?? "";
            $payment_refund->created_by = UserInfoHelper::employee_id();
            $payment_refund->save();
            // $payment_refund->cash_account_id = UserInfoHelper::get_user_id();
            
            // Input detail refund
            $refund_list = $request->refund_list;
            $refund_total = 0;

            foreach ($refund_list as $refund) {
                $invoice_detail_payment = InvoiceDetailPayment::find($refund["invoice_detail_payment_id"]);

                $payment_refund_detail = new PaymentRefundDetail;
                $payment_refund_detail->payment_refund_id = $payment_refund->id;
                $payment_refund_detail->invoice_detail_payment_id = $invoice_detail_payment->id;
                // Cari data payment detail

                // Catat data refund
                // update data pembayaran invoice detail
                $invoice_detail = InvoiceDetail::find($invoice_detail_payment->invoice_detail_id);
                $invoice_detail->status = "refund";

                // Cek Jika jumlah refund lebih dari jumlah yang dibayarkan
                if ($invoice_detail_payment->price > ($invoice_detail->payed_amount - $invoice_detail->refund_amount)) {
                    return ResponseHelper::response_error("Proses Refund Gagal", "Jumlah refund melebihi jumlah yang dibayarkan");
                }

                $invoice_detail->refund_amount = $invoice_detail->refund_amount + $invoice_detail_payment->price;
                $invoice_detail->save();

                // Catat detail refund
                $payment_refund_detail->amount = $invoice_detail_payment->price;
                $payment_refund_detail->save();

                // Update kolom jumlah refund di tabel detail pembayaran
                $invoice_detail_payment->refund_amount = $invoice_detail_payment->refund_amount + $invoice_detail_payment->price;
                $invoice_detail_payment->save();

                // Input ke cashflow refund
                // TODO: Ambil akun kas metode bayar CASH
                // TODO: Ambil akun kas iuran
                FinanceHelper::add_cashflow(
                    $bank_id, 
                    $invoice_detail_payment->price, 
                    0, 
                    $refund_code, 
                    $refund_code, 
                    $note, 
                    $payment_refund->created_by
                );

                $refund_total += $invoice_detail_payment->price;
            }

            $payment_refund->total = $refund_total;
            $payment_refund->save();


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Refund Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }
}
