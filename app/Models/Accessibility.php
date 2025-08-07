<?php

namespace App\Models;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\accessibilityInfoHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accessibility extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static function do_store(Request $request)
    {
        // Proses input
        try {
            DB::beginTransaction();

            // Proses Input data
            $accessibility = new Accessibility();
            $accessibility->name = $request->name;
            $accessibility->access = self::create_accessibility($request);
            $accessibility->created_by = UserInfoHelper::employee_id();
            $accessibility->save();


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal! " . $e);
        }
    }

    public static function do_update($id, Request $request)
    {
        // Proses update
        try {
            DB::beginTransaction();

            // Proses cari data
            $accessibility = Accessibility::find($id);

            // Cek data ada di database
            if ($accessibility == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");


            // Jika ada, lanjut update data
            $accessibility->name = $request->name;
            $accessibility->access = self::create_accessibility($request);
            $accessibility->updated_by = UserInfoHelper::employee_id();
            $accessibility->save();

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
            $accessibility = Accessibility::find($id);

            // Cek data ada di database
            if ($accessibility == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $accessibility->deleted_by = UserInfoHelper::employee_id();
            $accessibility->deleted_at = now();
            $accessibility->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
        }
    }

    public static function create_accessibility(Request $request)
    {
        return json_encode(
            [
                "accessibility" => [
                    "accessibility_all" => isset($request->accessibility_all) ? 1 : 0,
                    "accessibility_add" => isset($request->accessibility_add) ? 1 : 0,
                    "accessibility_view" => isset($request->accessibility_view) ? 1 : 0,
                    "accessibility_update" => isset($request->accessibility_update) ? 1 : 0,
                    "accessibility_delete" => isset($request->accessibility_delete) ? 1 : 0,
                    "accessibility_export" => isset($request->accessibility_export) ? 1 : 0,
                    "accessibility_import" => isset($request->accessibility_import) ? 1 : 0
                ],
                "student" => [
                    "student_all" => isset($request->student_all) ? 1 : 0,
                    "student_add" => isset($request->student_add) ? 1 : 0,
                    "student_view" => isset($request->student_view) ? 1 : 0,
                    "student_update" => isset($request->student_update) ? 1 : 0,
                    "student_delete" => isset($request->student_delete) ? 1 : 0,
                    "student_export" => isset($request->student_export) ? 1 : 0,
                    "student_import" => isset($request->student_import) ? 1 : 0
                ],
                "classroom" => [
                    "classroom_all" => isset($request->classroom_all) ? 1 : 0,
                    "classroom_add" => isset($request->classroom_add) ? 1 : 0,
                    "classroom_view" => isset($request->classroom_view) ? 1 : 0,
                    "classroom_update" => isset($request->classroom_update) ? 1 : 0,
                    "classroom_delete" => isset($request->classroom_delete) ? 1 : 0,
                    "classroom_export" => isset($request->classroom_export) ? 1 : 0,
                    "classroom_import" => isset($request->classroom_import) ? 1 : 0
                ],
                "due" => [
                    "due_all" => isset($request->due_all) ? 1 : 0,
                    "due_add" => isset($request->due_add) ? 1 : 0,
                    "due_view" => isset($request->due_view) ? 1 : 0,
                    "due_update" => isset($request->due_update) ? 1 : 0,
                    "due_delete" => isset($request->due_delete) ? 1 : 0,
                    "due_export" => isset($request->due_export) ? 1 : 0,
                    "due_import" => isset($request->due_import) ? 1 : 0
                ],
                "school_year" => [
                    "school_year_all" => isset($request->school_year_all) ? 1 : 0,
                    "school_year_add" => isset($request->school_year_add) ? 1 : 0,
                    "school_year_view" => isset($request->school_year_view) ? 1 : 0,
                    "school_year_update" => isset($request->school_year_update) ? 1 : 0,
                    "school_year_delete" => isset($request->school_year_delete) ? 1 : 0,
                    "school_year_export" => isset($request->school_year_export) ? 1 : 0,
                    "school_year_import" => isset($request->school_year_import) ? 1 : 0
                ],
                "due_day_off" => [
                    "due_day_off_all" => isset($request->due_day_off_all) ? 1 : 0,
                    "due_day_off_add" => isset($request->due_day_off_add) ? 1 : 0,
                    "due_day_off_view" => isset($request->due_day_off_view) ? 1 : 0,
                    "due_day_off_update" => isset($request->due_day_off_update) ? 1 : 0,
                    "due_day_off_delete" => isset($request->due_day_off_delete) ? 1 : 0,
                    "due_day_off_export" => isset($request->due_day_off_export) ? 1 : 0,
                    "due_day_off_import" => isset($request->due_day_off_import) ? 1 : 0
                ],
                "employee" => [
                    "employee_all" => isset($request->employee_all) ? 1 : 0,
                    "employee_add" => isset($request->employee_add) ? 1 : 0,
                    "employee_view" => isset($request->employee_view) ? 1 : 0,
                    "employee_update" => isset($request->employee_update) ? 1 : 0,
                    "employee_delete" => isset($request->employee_delete) ? 1 : 0,
                    "employee_export" => isset($request->employee_export) ? 1 : 0,
                    "employee_import" => isset($request->employee_import) ? 1 : 0
                ],
                "position" => [
                    "position_all" => isset($request->position_all) ? 1 : 0,
                    "position_add" => isset($request->position_add) ? 1 : 0,
                    "position_view" => isset($request->position_view) ? 1 : 0,
                    "position_update" => isset($request->position_update) ? 1 : 0,
                    "position_delete" => isset($request->position_delete) ? 1 : 0,
                    "position_export" => isset($request->position_export) ? 1 : 0,
                    "position_import" => isset($request->position_import) ? 1 : 0
                ],
                "user" => [
                    "user_all" => isset($request->user_all) ? 1 : 0,
                    "user_add" => isset($request->user_add) ? 1 : 0,
                    "user_view" => isset($request->user_view) ? 1 : 0,
                    "user_update" => isset($request->user_update) ? 1 : 0,
                    "user_delete" => isset($request->user_delete) ? 1 : 0,
                    "user_export" => isset($request->user_export) ? 1 : 0,
                    "user_import" => isset($request->user_import) ? 1 : 0
                ],
                "bank" => [
                    "bank_all" => isset($request->bank_all) ? 1 : 0,
                    "bank_add" => isset($request->bank_add) ? 1 : 0,
                    "bank_view" => isset($request->bank_view) ? 1 : 0,
                    "bank_update" => isset($request->bank_update) ? 1 : 0,
                    "bank_delete" => isset($request->bank_delete) ? 1 : 0,
                    "bank_export" => isset($request->bank_export) ? 1 : 0,
                    "bank_import" => isset($request->bank_import) ? 1 : 0
                ],
                "address_village" => [
                    "address_village_all" => isset($request->address_village_all) ? 1 : 0,
                    "address_village_add" => isset($request->address_village_add) ? 1 : 0,
                    "address_village_view" => isset($request->address_village_view) ? 1 : 0,
                    "address_village_update" => isset($request->address_village_update) ? 1 : 0,
                    "address_village_delete" => isset($request->address_village_delete) ? 1 : 0,
                    "address_village_export" => isset($request->address_village_export) ? 1 : 0,
                    "address_village_import" => isset($request->address_village_import) ? 1 : 0
                ],
                "address_district" => [
                    "address_district_all" => isset($request->address_district_all) ? 1 : 0,
                    "address_district_add" => isset($request->address_district_add) ? 1 : 0,
                    "address_district_view" => isset($request->address_district_view) ? 1 : 0,
                    "address_district_update" => isset($request->address_district_update) ? 1 : 0,
                    "address_district_delete" => isset($request->address_district_delete) ? 1 : 0,
                    "address_district_export" => isset($request->address_district_export) ? 1 : 0,
                    "address_district_import" => isset($request->address_district_import) ? 1 : 0
                ],
                "class_grade_promotion" => [
                    "class_grade_promotion_all" => isset($request->class_grade_promotion_all) ? 1 : 0,
                    "class_grade_promotion_view" => isset($request->class_grade_promotion_view) ? 1 : 0,
                    "class_grade_promotion_export" => isset($request->class_grade_promotion_export) ? 1 : 0,
                    "class_grade_promotion_import" => isset($request->class_grade_promotion_import) ? 1 : 0
                ],
                "class_change" => [
                    "class_change_all" => isset($request->class_change_all) ? 1 : 0,
                    "class_change_add" => isset($request->class_change_add) ? 1 : 0,
                    "class_change_view" => isset($request->class_change_view) ? 1 : 0,
                    "class_change_export" => isset($request->class_change_export) ? 1 : 0,
                    "class_change_import" => isset($request->class_change_import) ? 1 : 0
                ],
                "school_data" => [
                    "school_data_all" => isset($request->school_data_all) ? 1 : 0,
                    "school_data_tk" => isset($request->school_data_tk) ? 1 : 0,
                    "school_data_sd" => isset($request->school_data_sd) ? 1 : 0,
                    "school_data_smp" => isset($request->school_data_smp) ? 1 : 0
                ],
                "role" => [
                    "role_administration" => isset($request->role_administration) ? 1 : 0,
                    "role_cashier" => isset($request->role_cashier) ? 1 : 0,
                ],
                "coa" => [
                    "coa_all" => isset($request->coa_all) ? 1 : 0,
                    "coa_add" => isset($request->coa_add) ? 1 : 0,
                    "coa_view" => isset($request->coa_view) ? 1 : 0,
                    "coa_update" => isset($request->coa_update) ? 1 : 0,
                    "coa_delete" => isset($request->coa_delete) ? 1 : 0,
                    "coa_export" => isset($request->coa_export) ? 1 : 0,
                    "coa_import" => isset($request->coa_import) ? 1 : 0
                ],
                "cashflow" => [
                    "cashflow_all" => isset($request->cashflow_all) ? 1 : 0,
                    "cashflow_add" => isset($request->cashflow_add) ? 1 : 0,
                    "cashflow_view" => isset($request->cashflow_view) ? 1 : 0,
                    "cashflow_update" => isset($request->cashflow_update) ? 1 : 0,
                    "cashflow_delete" => isset($request->cashflow_delete) ? 1 : 0,
                    "cashflow_export" => isset($request->cashflow_export) ? 1 : 0,
                    "cashflow_import" => isset($request->cashflow_import) ? 1 : 0
                ],
                "due_subscription" => [
                    "due_subscription_all" => isset($request->due_subscription_all) ? 1 : 0,
                    "due_subscription_add" => isset($request->due_subscription_add) ? 1 : 0,
                    "due_subscription_view" => isset($request->due_subscription_view) ? 1 : 0,
                    "due_subscription_update" => isset($request->due_subscription_update) ? 1 : 0,
                    "due_subscription_delete" => isset($request->due_subscription_delete) ? 1 : 0,
                    "due_subscription_export" => isset($request->due_subscription_export) ? 1 : 0,
                    "due_subscription_import" => isset($request->due_subscription_import) ? 1 : 0
                ],
                "bill_issuance" => [
                    "bill_issuance_all" => isset($request->bill_issuance_all) ? 1 : 0,
                    "bill_issuance_add" => isset($request->bill_issuance_add) ? 1 : 0,
                    "bill_issuance_view" => isset($request->bill_issuance_view) ? 1 : 0,
                    "bill_issuance_update" => isset($request->bill_issuance_update) ? 1 : 0,
                    "bill_issuance_delete" => isset($request->bill_issuance_delete) ? 1 : 0,
                    "bill_issuance_export" => isset($request->bill_issuance_export) ? 1 : 0,
                    "bill_issuance_import" => isset($request->bill_issuance_import) ? 1 : 0
                ],
                "due_payment" => [
                    "due_payment_all" => isset($request->due_payment_all) ? 1 : 0,
                    "due_payment_add" => isset($request->due_payment_add) ? 1 : 0,
                    "due_payment_view" => isset($request->due_payment_view) ? 1 : 0,
                    "due_payment_update" => isset($request->due_payment_update) ? 1 : 0,
                    "due_payment_delete" => isset($request->due_payment_delete) ? 1 : 0,
                    "due_payment_export" => isset($request->due_payment_export) ? 1 : 0,
                    "due_payment_import" => isset($request->due_payment_import) ? 1 : 0
                ],
                "payment_refund" => [
                    "payment_refund_all" => isset($request->payment_refund_all) ? 1 : 0,
                    "payment_refund_add" => isset($request->payment_refund_add) ? 1 : 0,
                    "payment_refund_view" => isset($request->payment_refund_view) ? 1 : 0,
                    "payment_refund_update" => isset($request->payment_refund_update) ? 1 : 0,
                    "payment_refund_delete" => isset($request->payment_refund_delete) ? 1 : 0,
                    "payment_refund_export" => isset($request->payment_refund_export) ? 1 : 0,
                    "payment_refund_import" => isset($request->payment_refund_import) ? 1 : 0
                ],
                "cashier" => [
                    "cashier_all" => isset($request->cashier_all) ? 1 : 0,
                    "cashier_add" => isset($request->cashier_add) ? 1 : 0,
                    "cashier_view" => isset($request->cashier_view) ? 1 : 0,
                    "cashier_update" => isset($request->cashier_update) ? 1 : 0,
                    "cashier_delete" => isset($request->cashier_delete) ? 1 : 0,
                    "cashier_export" => isset($request->cashier_export) ? 1 : 0,
                    "cashier_import" => isset($request->cashier_import) ? 1 : 0
                ],
                "cashier_payment" => [
                    "cashier_payment_all" => isset($request->cashier_payment_all) ? 1 : 0,
                    "cashier_payment_add" => isset($request->cashier_payment_add) ? 1 : 0,
                    "cashier_payment_view" => isset($request->cashier_payment_view) ? 1 : 0,
                    "cashier_payment_update" => isset($request->cashier_payment_update) ? 1 : 0,
                    "cashier_payment_delete" => isset($request->cashier_payment_delete) ? 1 : 0,
                    "cashier_payment_export" => isset($request->cashier_payment_export) ? 1 : 0,
                    "cashier_payment_import" => isset($request->cashier_payment_import) ? 1 : 0
                ],
                "publish_va_manual" => [
                    "publish_va_manual_all" => isset($request->publish_va_manual_all) ? 1 : 0,
                    "publish_va_manual_add" => isset($request->publish_va_manual_add) ? 1 : 0,
                    "publish_va_manual_view" => isset($request->publish_va_manual_view) ? 1 : 0,
                    "publish_va_manual_update" => isset($request->publish_va_manual_update) ? 1 : 0,
                    "publish_va_manual_delete" => isset($request->publish_va_manual_delete) ? 1 : 0,
                    "publish_va_manual_export" => isset($request->publish_va_manual_export) ? 1 : 0,
                    "publish_va_manual_import" => isset($request->publish_va_manual_import) ? 1 : 0
                ],
                "payment_report" => [
                    "payment_report_all" => isset($request->payment_report_all) ? 1 : 0,
                    "payment_report_view" => isset($request->payment_report_view) ? 1 : 0,
                    "payment_report_export" => isset($request->payment_report_export) ? 1 : 0
                ],
                "balance_sheet_report" => [
                    "balance_sheet_report_all" => isset($request->balance_sheet_report_all) ? 1 : 0,
                    "balance_sheet_report_view" => isset($request->balance_sheet_report_view) ? 1 : 0,
                    "balance_sheet_report_export" => isset($request->balance_sheet_report_export) ? 1 : 0
                ],
                "cashflow_report" => [
                    "cashflow_report_all" => isset($request->cashflow_report_all) ? 1 : 0,
                    "cashflow_report_view" => isset($request->cashflow_report_view) ? 1 : 0,
                    "cashflow_report_export" => isset($request->cashflow_report_export) ? 1 : 0
                ],
                "expense_report" => [
                    "expense_report_all" => isset($request->expense_report_all) ? 1 : 0,
                    "expense_report_view" => isset($request->expense_report_view) ? 1 : 0,
                    "expense_report_export" => isset($request->expense_report_export) ? 1 : 0
                ],
                "profit_report" => [
                    "profit_report_all" => isset($request->profit_report_all) ? 1 : 0,
                    "profit_report_view" => isset($request->profit_report_view) ? 1 : 0,
                    "profit_report_export" => isset($request->profit_report_export) ? 1 : 0
                ],
                "cashier_report" => [
                    "cashier_report_all" => isset($request->cashier_report_all) ? 1 : 0,
                    "cashier_report_view" => isset($request->cashier_report_view) ? 1 : 0,
                    "cashier_report_export" => isset($request->cashier_report_export) ? 1 : 0
                ],
                "daily_bank_report" => [
                    "daily_bank_report_all" => isset($request->daily_bank_report_all) ? 1 : 0,
                    "daily_bank_report_view" => isset($request->daily_bank_report_view) ? 1 : 0,
                    "daily_bank_report_export" => isset($request->daily_bank_report_export) ? 1 : 0
                ],
                "student_not_paid_report" => [
                    "student_not_paid_report_all" => isset($request->student_not_paid_report_all) ? 1 : 0,
                    "student_not_paid_report_view" => isset($request->student_not_paid_report_view) ? 1 : 0,
                    "student_not_paid_report_export" => isset($request->student_not_paid_report_export) ? 1 : 0
                ],
                "student_paid_detail_report" => [
                    "student_paid_detail_report_all" => isset($request->student_paid_detail_report_all) ? 1 : 0,
                    "student_paid_detail_report_view" => isset($request->student_paid_detail_report_view) ? 1 : 0,
                    "student_paid_detail_report_export" => isset($request->student_paid_detail_report_export) ? 1 : 0
                ],
                "student_over_paid_report" => [
                    "student_over_paid_report_all" => isset($request->student_over_paid_report_all) ? 1 : 0,
                    "student_over_paid_report_view" => isset($request->student_over_paid_report_view) ? 1 : 0,
                    "student_over_paid_report_export" => isset($request->student_over_paid_report_export) ? 1 : 0
                ]
            ]
        );
    }
}
