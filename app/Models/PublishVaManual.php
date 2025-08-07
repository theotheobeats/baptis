<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use App\Helpers\PaymentGatewayHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublishVaManual extends Model
{
    use HasFactory;
    use HasTrackHistory;
    use SoftDeletes;

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $publish_va_manual = new PublishVaManual;
            $publish_va_manual->student_id = $request->student_id;
            $publish_va_manual->va_number = $request->va_number;
            $publish_va_manual->amount = $request->amount;
            $publish_va_manual->note = $request->note == null ? '' : $request->note;
            $publish_va_manual->created_by = UserInfoHelper::employee_id();
            $publish_va_manual->save();

            // Tutup tagihan by va number
            PaymentGatewayHelper::maspion_close_invoice($publish_va_manual->va_number);

            // Buka tagihan
            PaymentGatewayHelper::maspion_custom_send_invoice($publish_va_manual->va_number, $publish_va_manual->amount, $publish_va_manual->note);

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e->getMessage());
        }
    }

}
