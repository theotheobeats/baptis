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

class FinanceCashFlow extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "finance_cash_flows";

    public const SOURCE_BY_SYSTEM = "by_system";
    public const SOURCE_MANUAL = "manual";

    public const COA_SUB_DETAIL_BCA = "bca";
    public const COA_SUB_DETAIL_MASPION = "maspion";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // DEBIT - Proses Input data
            $qty = count(FinanceCashFlow::where("created_at", "LIKE", date("Y-m-d") . "%")->select("id")->get()) + 1;
            $code = "AK-" . date("ymd") . "-" . str_pad($qty, 6, "0", STR_PAD_LEFT);

            $finance_cash_flow = new FinanceCashFlow();
            $finance_cash_flow->source = FinanceCashFlow::SOURCE_MANUAL;
            $finance_cash_flow->account_id = $request->debit_account_id;
            $finance_cash_flow->code = $code;
            $finance_cash_flow->transaction_number = $request->transaction_number;
            $finance_cash_flow->transaction_date = $request->transaction_date;
            $finance_cash_flow->note = $request->note;
            $finance_cash_flow->credit = 0;
            $finance_cash_flow->debit = $request->amount;
            $finance_cash_flow->verified_at = now();
            $finance_cash_flow->verified_by = UserInfoHelper::employee_id();

            $finance_cash_flow->created_by = UserInfoHelper::employee_id();
            $finance_cash_flow->save();

            $debit_finance_cash_flow_id = $finance_cash_flow->id;

            // KREDIT - Proses Input data
            $qty = count(FinanceCashFlow::where("created_at", "LIKE", date("Y-m-d") . "%")->select("id")->get()) + 1;
            $code = "AK-" . date("ymd") . "-" . str_pad($qty, 6, "0", STR_PAD_LEFT);

            $finance_cash_flow = new FinanceCashFlow();
            $finance_cash_flow->source = FinanceCashFlow::SOURCE_MANUAL;
            $finance_cash_flow->account_id = $request->credit_account_id;
            $finance_cash_flow->code = $code;
            $finance_cash_flow->transaction_number = $request->transaction_number;
            $finance_cash_flow->transaction_date = $request->transaction_date;
            $finance_cash_flow->note = $request->note;
            $finance_cash_flow->credit = $request->amount;
            $finance_cash_flow->debit = 0;
            $finance_cash_flow->verified_at = now();
            $finance_cash_flow->verified_by = UserInfoHelper::employee_id();

            $finance_cash_flow->created_by = UserInfoHelper::employee_id();
            $finance_cash_flow->save();

            $credit_finance_cash_flow_id = $finance_cash_flow->id;

            // Handle file handover
            if ($request->file_handover != null) {
                $file_handover_list = $request->file_handover;
                for ($i = 0; $i < count($file_handover_list); $i++) {
                    $finance_cash_flow_photo_handover = $file_handover_list[$i];
                    $finance_cash_flow_photo_handover_name = time() . "_debit_" . $finance_cash_flow_photo_handover->getClientOriginalName();
                    $finance_cash_flow_photo_handover_path = UploadFilePathHelper::FINANCE_CASH_FLOW_PHOTO_HANDOVER_PATH;

                    $finance_cash_flow_photo_handover->move($finance_cash_flow_photo_handover_path, $finance_cash_flow_photo_handover_name);
                    $full_path = $finance_cash_flow_photo_handover_path . '/' . $finance_cash_flow_photo_handover_name;

                    $finance_cash_flow_file_debit = new FinanceCashFlowFile();
                    $finance_cash_flow_file_debit->file_handover = $full_path;
                    $finance_cash_flow_file_debit->finance_cash_flow_id = $debit_finance_cash_flow_id;
                    $finance_cash_flow_file_debit->created_by = UserInfoHelper::employee_id();
                    $finance_cash_flow_file_debit->save();

                    $finance_cash_flow_photo_handover_name_credit = time() . "_credit_" . $finance_cash_flow_photo_handover->getClientOriginalName();
                    $full_path_credit = $finance_cash_flow_photo_handover_path . '/' . $finance_cash_flow_photo_handover_name_credit;

                    copy($full_path, $full_path_credit);

                    $finance_cash_flow_file_credit = new FinanceCashFlowFile();
                    $finance_cash_flow_file_credit->file_handover = $full_path_credit;
                    $finance_cash_flow_file_credit->finance_cash_flow_id = $credit_finance_cash_flow_id;
                    $finance_cash_flow_file_credit->created_by = UserInfoHelper::employee_id();
                    $finance_cash_flow_file_credit->save();
                }
            }

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!");
        }
    }

    public static function do_store2(Request $request)
    {
        try {
            DB::beginTransaction();

            $transaction_number = $request->transaction_number;
            $transaction_date = $request->transaction_date;
            $note = $request->note;
            $items = $request->items;

            // Proses Input data
            foreach ($items as $item) {
                $count = count(FinanceCashFlow::where("created_at", "LIKE", date("Y-m-d") . "%")->select("id")->get()) + 1;
                $code = "AK-" . date("ymd") . "-" . str_pad($count, 6, "0", STR_PAD_LEFT);

                $account = FinanceAccount::find($item['account_id']);

                $finance_cash_flow = new FinanceCashFlow();
                $finance_cash_flow->source = FinanceCashFlow::SOURCE_MANUAL;
                $finance_cash_flow->account_id = $item['account_id'];
                $finance_cash_flow->coa_sub_detail_name = $account->sub_detail;
                $finance_cash_flow->sub_detail = $item['sub_detail'] ?? null;
                $finance_cash_flow->code = $code;
                $finance_cash_flow->transaction_number = $transaction_number;
                $finance_cash_flow->transaction_date = $transaction_date;
                $finance_cash_flow->note = $note;
                $finance_cash_flow->debit = $item['debit'] ?? 0;
                $finance_cash_flow->credit = $item['credit'] ?? 0;
                $finance_cash_flow->verified_at = now();
                $finance_cash_flow->verified_by = UserInfoHelper::employee_id();
                $finance_cash_flow->created_by = UserInfoHelper::employee_id();
                $finance_cash_flow->save();

                if (isset($item['file_handover'])) {
                    $file_handover_list = $item['file_handover'];
                    for ($i = 0; $i < count($file_handover_list); $i++) {
                        $finance_cash_flow_file = new FinanceCashFlowFile();
                        $finance_cash_flow_photo_handover_path = UploadFilePathHelper::FINANCE_CASH_FLOW_PHOTO_HANDOVER_PATH;
                        $finance_cash_flow_photo_handover = $file_handover_list[$i];
                        $finance_cash_flow_photo_handover_name = time() . "_" . $finance_cash_flow_photo_handover->getClientOriginalName();
                        $finance_cash_flow_photo_handover->move($finance_cash_flow_photo_handover_path, $finance_cash_flow_photo_handover_name);
                        $finance_cash_flow_file->file_handover = $finance_cash_flow_photo_handover_path . '/' . $finance_cash_flow_photo_handover_name;
                        $finance_cash_flow_file->finance_cash_flow_id = $finance_cash_flow->id;
                        $finance_cash_flow_file->created_by = UserInfoHelper::employee_id();
                        $finance_cash_flow_file->save();
                    }
                }
            }

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
            $finance_cash_flow = FinanceCashFlow::find($id);

            // Cek data ada di database
            if ($finance_cash_flow == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $finance_cash_flow->account_id = $request->account_id;
            $finance_cash_flow->transaction_number = $request->transaction_number;
            $finance_cash_flow->transaction_date = $request->transaction_date;
            $finance_cash_flow->note = $request->note;
            $finance_cash_flow->debit = $request->debit;
            $finance_cash_flow->credit = $request->credit;

            // Handle file handover
            if ($request->file_handover != null) {
                $file_handover_list = $request->file_handover;
                for ($i = 0; $i < count($file_handover_list); $i++) {
                    $finance_cash_flow_file = new FinanceCashFlowFile();
                    $finance_cash_flow_photo_handover_path = UploadFilePathHelper::FINANCE_CASH_FLOW_PHOTO_HANDOVER_PATH;
                    $finance_cash_flow_photo_handover = $file_handover_list[$i];
                    $finance_cash_flow_photo_handover_name = time() . "_" . $finance_cash_flow_photo_handover->getClientOriginalName();
                    $finance_cash_flow_photo_handover->move($finance_cash_flow_photo_handover_path, $finance_cash_flow_photo_handover_name);
                    $finance_cash_flow_file->file_handover = $finance_cash_flow_photo_handover_path . '/' . $finance_cash_flow_photo_handover_name;
                    $finance_cash_flow_file->finance_cash_flow_id = $finance_cash_flow->id;
                    $finance_cash_flow_file->created_by = UserInfoHelper::employee_id();
                    $finance_cash_flow_file->save();
                }
            }

            $finance_cash_flow->updated_by = UserInfoHelper::employee_id();
            $finance_cash_flow->save();

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
            $finance_cash_flow = FinanceCashFlow::find($id);

            // Cek data ada di database
            if ($finance_cash_flow == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            $finance_cash_flow_files = FinanceCashFlowFile::where("finance_cash_flow_id", $finance_cash_flow->id)->get();
            foreach ($finance_cash_flow_files as $finance_cash_flow_file) {
                $finance_cash_flow_file->deleted_at = now();
                $finance_cash_flow_file->deleted_by = UserInfoHelper::employee_id();
                $finance_cash_flow_file->save();
            }

            // Jika ada, input data yang hapus
            $finance_cash_flow->deleted_by = UserInfoHelper::employee_id();
            $finance_cash_flow->deleted_at = now();
            $finance_cash_flow->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
        }
    }

    public static function do_verify($id)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $finance_cash_flow = FinanceCashFlow::find($id);

            // Cek data ada di database
            if ($finance_cash_flow == null) return ResponseHelper::response_error("Proses Verifikasi Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $finance_cash_flow->verified_at = now();
            $finance_cash_flow->verified_by = UserInfoHelper::employee_id();
            $finance_cash_flow->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Verifikasi Berhasil", "Data telah diverifikasi");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Verifikasi Gagal", "Proses verifikasi data gagal!");
        }
    }
}
