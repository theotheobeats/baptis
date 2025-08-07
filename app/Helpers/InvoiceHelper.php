<?php

namespace App\Helpers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceReconciliation;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentDue;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InvoiceHelper
{
    public static function publish_invoice() {
        // Close all invoice
        // Terbitkan tagihan
        // Kirim notifikasi WA
        
        try {
            DB::beginTransaction();
            $bill_date = date("Y-m-d");

            $invoice_due_date = "10";
            $invoice_month = isset($bill_date) ? substr($bill_date, 5, 2) : date("m");
            $invoice_year = isset($bill_date) ? substr($bill_date, 0, 4) : date("Y");

            $invoice_month = $invoice_month == null ? date("m") : $invoice_month;
            $invoice_year = $invoice_year == null ? date("Y") : $invoice_year;
            // Ambil daftar iuran yang aktif
            // Kalkulasi jumlah iuran
            // Cek apakah sudah ada invoice yang terbit dibulan ini
            $active_students = Student::whereNull("non_active_at")->get();
            // Ambil daftar iuran siswa
            foreach ($active_students as $active_student) {
                // Cek apakah sudah ada tagihan terbuat atas nama siswa terpilih

                $invoice = Invoice::where("student_id", "=", $active_student->id)
                    // ->where("payment_for_month", "=", $invoice_month)
                    // ->where("payment_for_year", "=", $invoice_year)
                    ->first();


                // Jika tidak ada invoice terbuat
                if ($invoice == null) {
                    // Buat header invoice
                    $invoice = new Invoice;
                    $invoice->price = 0;
                    $invoice->payed_amount = 0;
                    $invoice->bill_price = 0;
                    $invoice->save();
                }

                $invoice->student_id = $active_student->id;
                $invoice->payment_for_month = $invoice_month;
                $invoice->payment_for_year = $invoice_year;
                $invoice->payment_due_date = $invoice_year . "-" . $invoice_month . "-" . $invoice_due_date . " 23:59:59";
                $invoice->save();

                // Data kelas siswa sekarang
                $student_classroom = StudentClassroom::where("student_id", "=", $active_student->id)
                    ->where("is_active", 1)
                    ->first();

                // Hitung total iuran siswa
                $student_dues = StudentDue::where("student_id", "=", $active_student->id)->get();
                $grand_total = 0;
                foreach ($student_dues as $student_due) {

                    // Cek apakah sudah ada invoice terbit di bulan terpilih
                    $current_selected_student_id = $active_student->id;

                    $has_invoice_detail = InvoiceDetail::where("due_id", "=", $student_due->due_id)
                        ->where("payment_for_month", "=", $invoice_month)
                        ->where("payment_for_year", "=", $invoice_year)
                        ->where("backtrack_student_id", "=", $current_selected_student_id)
                        ->first();
                    if ($has_invoice_detail == null) {
                        $invoice_detail = new InvoiceDetail;
                        $invoice_detail->invoice_id = $invoice->id;
                        $invoice_detail->due_id = $student_due->due_id;
                        $invoice_detail->price = $student_due->price;
                        $invoice_detail->backtrack_student_id = $current_selected_student_id;
                        $invoice_detail->payment_for_month = $invoice_month;
                        $invoice_detail->payment_for_year = $invoice_year;
                        $invoice_detail->classroom_id = $student_classroom->classroom_id;
                        $invoice_detail->school_year_id = $student_classroom->school_year_id;
                        $invoice_detail->payment_due_date = $invoice_year . "-" . $invoice_month . "-" . $invoice_due_date . " 23:59:59";
                        $invoice_detail->save();

                        $grand_total += $student_due->price;
                    }
                }

                // Update grand total tagihan
                $invoice->price = $invoice->price + $grand_total;
                $invoice->bill_price = $invoice->bill_price + $grand_total;
                $invoice->save();
            }


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Invoice telah diterbitkan", "Tagihan telah terbit");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }
}