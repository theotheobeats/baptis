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
use App\Helpers\UploadFilePathHelper;

class FinanceCashFlowFile extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "finance_cash_flow_files";

    public static function do_delete($id)
    {
        try {
            DB::beginTransaction();

            $finance_cash_flow_file = FinanceCashFlowFile::find($id);

            if ($finance_cash_flow_file == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            $finance_cash_flow_file->deleted_at = now();
            $finance_cash_flow_file->deleted_by = UserInfoHelper::employee_id();
            $finance_cash_flow_file->save();

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
