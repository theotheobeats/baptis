<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use App\Helpers\InvoicePublisherHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "invoices";

    public static function do_store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $invoice = new Invoice();
            $invoice->student_id = $request->student_id;
            $invoice->status = $request->status;
            $invoice->price = $request->price;
            $invoice->payed_amount = 0;
            $invoice->note = $request->note;

            $invoice->created_by = UserInfoHelper::employee_id();
            $invoice->save();

            $grand_total = 0;
            if (isset($request->due_id)) {
                for ($i = 0; $i < count($request->due_id); $i++) {
                    $invoice_details = new InvoiceDetail();
                    $invoice_details->invoice_id = $invoice->id;
                    $invoice_details->due_id = $request->due_id[$i];
                    $invoice_details->price = $request->due_price[$i];
                    $invoice_details->created_by = UserInfoHelper::employee_id();
                    $invoice_details->save();

                    $grand_total = $grand_total + $request->due_price[$i];
                }
            }
            $invoice->price = $grand_total;
            $invoice->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }



    // Generate invoice bulanan
    public static function publish_monthly_invoice(Request $request)
    {
        try {
            DB::beginTransaction();
            $invoice_due_date = "10";
            $invoice_month = $request->month;
            $invoice_year = $request->year;

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

                    $invoice_detail = new InvoiceDetail;
                    $invoice_detail->invoice_id = $invoice->id;
                    $invoice_detail->due_id = $student_due->due_id;
                    $invoice_detail->price = $student_due->price;
                    $invoice_detail->backtrack_student_id = $active_student->id;
                    $invoice_detail->payment_for_month = $invoice_month;
                    $invoice_detail->payment_for_year = $invoice_year;
                    $invoice_detail->classroom_id = $student_classroom->classroom_id;
                    $invoice_detail->school_year_id = $student_classroom->school_year_id;
                    $invoice_detail->payment_due_date = $invoice_year . "-" . $invoice_month . "-" . $invoice_due_date . " 23:59:59";
                    $invoice_detail->save();

                    $grand_total += $student_due->price;
                }

                // Update grand total tagihan
                $invoice->price = $invoice->price + $grand_total;
                $invoice->bill_price = $invoice->bill_price + $grand_total;
                $invoice->save();
            }


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return response()->json("Invoice telah diterbitkan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }



    // Publish invoice jika sudah ada tagihan yang sama maka tidak akan ditagih lagi
    public static function improved_publish_monthly_invoice(Request $request)
    {
        ini_set('max_execution_time', 300);
        try {
            DB::beginTransaction();
            $bill_date = $request->bill_date;

            $invoice_due_date = "10";
            $invoice_month = isset($bill_date) ? substr($bill_date, 5, 2) : $request->month;
            $invoice_year = isset($bill_date) ? substr($bill_date, 0, 4) : $request->year;

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
                        $month_invoice_detail_amount = count(InvoiceDetail::withTrashed()->where("created_at", "LIKE", date("Y-m") . "%")
                        ->select("id")->get()) + 1;
                        $code_detail = "INVD-" . date("ymd") . "-" . str_pad($month_invoice_detail_amount++, 4, "0", STR_PAD_LEFT);

                        $invoice_detail = new InvoiceDetail;
                        $invoice_detail->invoice_id = $invoice->id;
                        $invoice_detail->due_id = $student_due->due_id;
                        $invoice_detail->code = $code_detail;
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
            
            // TODO: Terbitkan notifikasi ke orang tua siswa
            // TODO: Terbitkan tagihan ke bank


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Invoice telah diterbitkan", "Tagihan telah terbit");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }



    public static function improved_publish_individual_invoice(Request $request)
    {
        try {
            DB::beginTransaction();

            $bill_date_from = $request->date_from;
            $bill_date_to = $request->date_to;

            $invoice_due_date = "10";
            $start_date = new DateTime($bill_date_from);
            $end_date = new DateTime($bill_date_to);

            $end_date->modify('last day of this month');
            $interval = new DateInterval('P1M');
            $period = new DatePeriod($start_date, $interval, $end_date);

            $months = [];
            $years = [];
            foreach ($period as $date) {
                $months[] = $date->format('m');
                $years[] = $date->format('Y');
            }

            // Ambil daftar iuran yang aktif
            // Kalkulasi jumlah iuran
            // Cek apakah sudah ada invoice yang terbit dibulan ini
            $active_student = Student::find($request->student_id);
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
            $invoice->payment_for_month = $months[0];
            $invoice->payment_for_year = $years[0];
            $invoice->payment_due_date = $years[0] . "-" . $months[0] . "-" . $invoice_due_date . " 23:59:59";
            $invoice->save();

            // Data kelas siswa sekarang
            $student_classroom = StudentClassroom::where("student_id", "=", $active_student->id)
                ->where("is_active", 1)
                ->first();

            // Hitung total iuran siswa
            $student_dues = StudentDue::where("student_id", "=", $active_student->id)->get();
            $grand_total = 0;

            for ($i = 0; $i < count($months); $i++) {

                foreach ($student_dues as $student_due) {

                    // Cek apakah sudah ada invoice terbit di bulan terpilih
                    $current_selected_student_id = $active_student->id;
    
                    $has_invoice_detail = InvoiceDetail::where("due_id", "=", $student_due->due_id)
                        ->where("payment_for_month", "=", $months[$i])
                        ->where("payment_for_year", "=", $years[$i])
                        ->where("backtrack_student_id", "=", $current_selected_student_id)
                        ->first();

                    if ($has_invoice_detail == null) {
                        $month_invoice_detail_amount = count(InvoiceDetail::withTrashed()->where("created_at", "LIKE", date("Y-m") . "%")->select("id")->get()) + 1;
                        $code_detail = "INVD-" . date("ymd") . "-" . str_pad($month_invoice_detail_amount++, 4, "0", STR_PAD_LEFT);
    
                        $invoice_detail = new InvoiceDetail;
                        $invoice_detail->invoice_id = $invoice->id;
                        $invoice_detail->due_id = $student_due->due_id;
                        $invoice_detail->code = $code_detail;
                        $invoice_detail->price = $student_due->price;
                        $invoice_detail->backtrack_student_id = $current_selected_student_id;
                        $invoice_detail->payment_for_month = $months[$i];
                        $invoice_detail->payment_for_year = $years[$i];
                        $invoice_detail->status = "open";
                        $invoice_detail->classroom_id = $student_classroom->classroom_id;
                        $invoice_detail->school_year_id = $student_classroom->school_year_id;
                        $invoice_detail->payment_due_date = $years[$i] . "-" . $months[$i] . "-" . $invoice_due_date . " 23:59:59";
                        $invoice_detail->backtrack_student_id = $active_student->id;
                        $invoice_detail->save();
    
                        $grand_total += $student_due->price;
                    }
                }
            }


            // Update grand total tagihan
            $invoice->price = $invoice->price + $grand_total;
            $invoice->bill_price = $invoice->bill_price + $grand_total;
            $invoice->save();
            
            // Terbitkan tagihan ke bank, untuk notifikasi manual
            // InvoicePublisherHelper::publish_single_invoice($invoice->id);
            InvoicePublisherHelper::publish_single_invoice($invoice);


            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Invoice telah diterbitkan", "Tagihan telah terbit");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }



    // Sesuaikan nilai invoice
}
