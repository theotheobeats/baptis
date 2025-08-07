<?php

namespace App\Helpers;

use App\Models\Classroom;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceReconciliation;
use App\Models\InvoiceReconciliationDetail;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentDue;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InvoicePublisherHelper
{
    // Perlu make sure nama bulan di invoice header sudah benar
    // Kembalikan koding ke live server
    // Perlu testing publi

    public static function publish_all_invoice() {
        // Set timeout limit 1 jam
        set_time_limit(3600);

        // Ambil daftar tagihan yang nilainya masih diatas 0
        // $invoice = Invoice::where("bill_price", ">", 0)->where("invoice_type", "=", "monthly_fee")->first();
        $invoices = Invoice::where("bill_price", ">", 0)->where("invoice_type", "=", "monthly_fee")->get();
        foreach ($invoices as $invoice) {
            self::publish_single_invoice($invoice);
        }

        // Perlu get VA number
        // Perlu handling jika tidak ada data
        // Buat web service untuk return data

    }

    public static function publish_all_notification() {
        set_time_limit(3600);

        $invoices = Invoice::where("bill_price", ">", 0)->where("invoice_type", "=", "monthly_fee")->get();
        foreach ($invoices as $invoice) {
            self::publish_notification_only($invoice);
        }
    }



    public static function publish_single_invoice($invoice)
    {
        $with_whatsapp_notification = true;

        // Cari data siswa
        $student = Student::withTrashed()->find($invoice->student_id);
        if ($student == null) {
            return false;
        }

        if ($student->non_active_at != null) {
            return false;
        }

        // Tutup semua invoice reconciliation atas nomor invoice berikut yang masih pending
        $old_invoice_reconciliation = InvoiceReconciliation::whereNull("inactive_at")
            ->where("invoice_id", "=", $invoice->id)
            ->first();

        // Nilai tagihan
        $bill_price = $invoice->bill_price;

        // Buat invoice reconciliation baru
        $invoice_reconciliation = new InvoiceReconciliation;
        $invoice_reconciliation->invoice_id = $invoice->id;
        $invoice_reconciliation->student_id = $invoice->student_id;
        $invoice_reconciliation->base_va_section_1 = "01391";
        $invoice_reconciliation->base_va_section_2 = $student->backtrack_class_grade;
        $invoice_reconciliation->base_va_section_3 = $student->nis;
        $invoice_reconciliation->va_number = "";
        $invoice_reconciliation->maspion_va_number = $invoice_reconciliation->base_va_section_2 . $invoice_reconciliation->base_va_section_3;
        $invoice_reconciliation->bca_va_number = "";
        $invoice_reconciliation->invoice_amount = $bill_price;
        $invoice_reconciliation->save();

        $bca_va_number = "-";


        // Ambil daftar iuran yang akan ditagih
        $invoice_detail_list = InvoiceDetail::where("invoice_id", "=", $invoice->id)
            ->where("status", "=", "open")
            ->select("id", "invoice_id", "price", "payed_amount")
            ->get();

        // Input kedalam detail rekonsialiasi tagihan
        foreach ($invoice_detail_list as $_invoice_detail) {
            $invoice_reconciliation_detail = new InvoiceReconciliationDetail;
            $invoice_reconciliation_detail->invoice_reconciliation_id = $invoice_reconciliation->id;
            $invoice_reconciliation_detail->invoice_detail_id = $_invoice_detail->id;
            $invoice_reconciliation_detail->invoice_amount = $_invoice_detail->price - $_invoice_detail->payed_amount;
            $invoice_reconciliation_detail->created_by = $_invoice_detail->price - $_invoice_detail->payed_amount;
            $invoice_reconciliation_detail->save();
        }




        // Tutup invoice lama
        if ($old_invoice_reconciliation != null) {
            PaymentGatewayHelper::maspion_close_invoice($old_invoice_reconciliation->maspion_va_number);
        }

        // Buat invoice baru
        $maspion_send_invoice = PaymentGatewayHelper::maspion_send_invoice($invoice_reconciliation->id);

        // Kirim notif WA
        // if ($student != null) {
        //     $invoice_details = InvoiceDetail::join("dues", "dues.id", "=", "invoice_details.due_id")
        //         ->where("invoice_id", "=", $invoice->id)
        //         ->where("status", "=", "open")
        //         ->select("dues.name as due_name", DB::raw("SUM(invoice_details.price) as price"), DB::raw("SUM(invoice_details.payed_amount) as payed_amount"))
        //         ->groupBy("due_name")
        //         // ->orderBy("due_name", "asc")
        //         ->get();
        //     // Generate note
        //     $payment_for_month = $invoice->payment_for_month;
        //     $payment_for_month_name = DataHelper::get_month_name(intval($payment_for_month) - 1);
        //     $note = "";
        //     $i = 1;
        //     foreach ($invoice_details as $invoice_detail) {
        //         $note .= ($i++ . ". " . $invoice_detail->due_name . " : " . number_format($invoice_detail->price - $invoice_detail->payed_amount)) . "\\n";
        //     }

        //     $bca_va_number = "06489" . $student->backtrack_class_grade . $student->nis;
        //     $invoice_path = InvoicePublisherHelper::generate_invoice_file($invoice->id, $maspion_send_invoice["va_number"], $bca_va_number);

        //     if ($with_whatsapp_notification) {
        //         $whatsapp_notification_result = WhatsappNotificationHelper::send_invoice_notification_message_template_custom(
        //             [
        //                 "user_name" => $student->name == null ? "NULL" : $student->name,// "dewi",
        //                 "number" => $student->backtrack_student_whatsapp_number,
        //                 "variabel" => [
        //                     "{{1}}" => "(text)01 " . $payment_for_month_name . " " . date("Y"),
        //                     "{{2}}" => "(text)" . $student->name,
        //                     "{{3}}" => "(text)" . $payment_for_month_name . date("Y"),
        //                     "{{4}}" => "(text)" . $invoice_path,
        //                 ]
        //             ]
        //         );
        //         // $whatsapp_notification_result = WhatsappNotificationHelper::send_invoice_notification_message_template_custom(
        //         //     [
        //         //         "user_name" => "dewi",
        //         //         "number" => $student->backtrack_student_whatsapp_number,
        //         //         "variabel" => [
        //         //             "{{1}}" => "(text)01 " . $payment_for_month_name . " " . date("Y"),
        //         //             "{{2}}" => "(text)" . $student->name,
        //         //             "{{3}}" => "(text)" . $payment_for_month_name,
        //         //             "{{4}}" => "(text)" . $note,
        //         //             "{{5}}" => "(text)" . number_format($bill_price),
        //         //             "{{6}}" => "(text) Maspion " . $maspion_send_invoice["va_number"],
        //         //         ]
        //         //     ]
        //         // );
        //     }
        //     // dd ($whatsapp_notification_result);
        // }
    }

    public static function single_invoice_publish_and_notification($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        self::publish_single_invoice($invoice);
        self::publish_notification_only($invoice);
    }

    public static function generate_invoice_file($invoice_id, $va_number = "-", $bca_va_number = "-")
    {
        // $decrypt = CryptoHelper::decrypt($id);
        // if (!$decrypt->success) return $decrypt->error_response;
        // $invoice_payment = new InvoicePayment;
        // $invoice_payment = $invoice_payment->join("invoices", "invoices.id", "=", "invoice_payments.invoice_id");
        // $invoice_payment = $invoice_payment->where("invoice_payments.id", "=", $payment_id);
        // $invoice_payment = $invoice_payment->select(
        //     "invoice_payments.*",
        //     "invoices.student_id",
        // );
        // $invoice_payment = $invoice_payment->first();

        // if($invoice_payment == null){ return abort(404); }
        // $invoice_detail_payments = new InvoiceDetailPayment;
        // $invoice_detail_payments = $invoice_detail_payments->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id");
        // $invoice_detail_payments = $invoice_detail_payments->join("dues", "dues.id", "=", "invoice_details.due_id");
        // $invoice_detail_payments = $invoice_detail_payments->where("invoice_detail_payments.invoice_payment_id", "=", $invoice_payment->id);
        // $invoice_detail_payments = $invoice_detail_payments->select(
        //     "invoice_detail_payments.*",
        //     "invoice_detail_payments.price as price",
        //     "invoice_details.payment_for_month as invoice_detail_payment_for_month",
        //     "invoice_details.payment_for_year as invoice_detail_payment_for_year",
        //     "dues.name as due_name",
        // );
        // $invoice_detail_payments = $invoice_detail_payments->get();
        $invoice = Invoice::find($invoice_id);

        $invoice_details = new InvoiceDetail;
        $invoice_details = $invoice_details->join("dues", "dues.id", "=", "invoice_details.due_id");
        $invoice_details = $invoice_details->where("invoice_details.invoice_id", "=", $invoice_id);
        $invoice_details = $invoice_details->where("invoice_details.status", "=", "open");

        $invoice_details = $invoice_details->select(
            "invoice_details.*",
            "dues.name as due_name",
        );
        $invoice_details = $invoice_details->get();

        $student = Student::find($invoice->student_id);

        $student_school_group = Classroom::find($student->backtrack_current_classroom_id)->school_group;
        // if ($student_school_group == "TK") {
        //     $doc = 'docs.invoice-tk';
        // } else if ($student_school_group == "SD") {
        //     $doc = 'docs.invoice-sd';
        // } else if ($student_school_group == "SMP") {
        //     $doc = 'docs.invoice-smp';
        // } else {
            $doc = 'docs.invoice';
        // }

        $pdf = Pdf::loadView($doc, [
            "invoice" => $invoice,
            "invoice_details" => $invoice_details,
            "student" => $student,
            "va_number" => $va_number,
            "bca_va_number" => $bca_va_number,
            "is_pdf" => "true"
        ]);
        // $_relative_filepath = "invoice/" . $student->id . "-" . $student->nis . "-" . time() . ".pdf";
        $_relative_filepath = "invoice/" . $student->nis . "-" . date('Ymd') . "-" . $student->id . "-" . time() . ".pdf";
        $file_path = public_path($_relative_filepath);
        $student_password = "19900101";
        if ($student != null && $student->birth_date != null && $student->birth_date != "") {
            $student_password = date('dmY', strtotime($student->birth_date));
        }
        $pdf->setEncryption("PASS@SISXBAPTIS2024", $student_password, ['copy', 'print']);
        $file_output = $pdf->output();
        // Storage::put($file_path, $pdf->output());
        file_put_contents($file_path, $file_output);
        return "https://accounting.sekolahbaptispalembang.com/" . $_relative_filepath; // $pdf->download('invoice.pdf');
    }

    public static function example_invoice_format($invoice_id, $va_number = "-", $bca_va_number = "-")
    {
        $invoice = Invoice::find($invoice_id);

        $invoice_details = new InvoiceDetail;
        $invoice_details = $invoice_details->join("dues", "dues.id", "=", "invoice_details.due_id");
        $invoice_details = $invoice_details->where("invoice_details.invoice_id", "=", $invoice_id);
        $invoice_details = $invoice_details->where("invoice_details.status", "=", "open");

        $invoice_details = $invoice_details->select(
            "invoice_details.*",
            "dues.name as due_name",
        );
        $invoice_details = $invoice_details->get();

        $student = Student::find($invoice->student_id);

        $student_school_group = Classroom::find($student->backtrack_current_classroom_id)->school_group;
        if ($student_school_group == "TK") {
            $doc = 'docs.invoice-tk';
        } else if ($student_school_group == "SD") {
            $doc = 'docs.invoice-sd';
        } else if ($student_school_group == "SMP") {
            $doc = 'docs.invoice-smp';
        } else {
            $doc = 'docs.invoice';
        }

        // return view($doc, [
        //     "invoice" => $invoice,
        //     "invoice_details" => $invoice_details,
        //     "student" => $student,
        //     "va_number" => $va_number,
        //     "bca_va_number" => $bca_va_number,
        //     "is_pdf" => "false"
        // ]);

        $pdf = Pdf::loadView($doc, [
            "invoice" => $invoice,
            "invoice_details" => $invoice_details,
            "student" => $student,
            "va_number" => $va_number,
            "bca_va_number" => $bca_va_number,
            "is_pdf" => "true"
        ]);

        $directory_path = public_path("invoice");
        if (!is_dir($directory_path)) {
            mkdir($directory_path, 0755, true);
        }

        $file_path = public_path("invoice/" . $student->id . "-" . $student->nis . "-" . time() . ".pdf");
        $student_password = "19900101";
        if ($student != null && $student->birth_date != null && $student->birth_date != "") {
            $student_password = date('Ymd', strtotime($student->birth_date));
        }
        // $pdf->setEncryption("PASS@SISXBAPTIS2024", $student_password, ['copy', 'print']);
        $file_output = $pdf->output();
        // Storage::put($file_path, $pdf->output());
        // file_put_contents($file_path, $file_output);
        // return "http://127.0.0.1:8000/" . $file_path; // $pdf->download('invoice.pdf');
        return $pdf->stream('invoice.pdf');
    }



    public static function publish_invoice_without_notification()
    {

    }

    public static function publish_notification_only($invoice)
    {
        $student = Student::withTrashed()->find($invoice->student_id);
        if ($student == null) {
            return false;
        }

        // if ($student->id != 10) {
        //     return false;
        // }

        if ($student->non_active_at != null) {
            return false;
        }

        $invoice_reconciliation = InvoiceReconciliation::withTrashed()->where("invoice_id", "=", $invoice->id)->orderBy("id", "desc")->whereNull("inactive_at")->first();

        if ($invoice_reconciliation != null && $student != null) {
            $invoice_details = InvoiceDetail::join("dues", "dues.id", "=", "invoice_details.due_id")
                ->where("invoice_id", "=", $invoice->id)
                // ->whereRaw("invoice_details.price != invoice_details.payed_amount")
                ->where("status", "=", "open")
                ->select("dues.name as due_name", DB::raw("SUM(invoice_details.price) as price"), DB::raw("SUM(invoice_details.payed_amount) as payed_amount"))
                ->groupBy("due_name")
                // ->orderBy("due_name", "asc")
                ->get();
            // Generate note
            $payment_for_month = $invoice->payment_for_month;
            $payment_for_month_name = DataHelper::get_month_name(intval($payment_for_month) - 1);
            $note = "";
            $i = 1;
            foreach ($invoice_details as $invoice_detail) {
                $note .= ($i++ . ". " . $invoice_detail->due_name . " : " . number_format($invoice_detail->price - $invoice_detail->payed_amount)) . "\\n";
            }

            $va_number = $invoice_reconciliation->maspion_va_number;
            $bca_va_number = "06489" . $student->backtrack_class_grade . $student->nis;
            $invoice_path = InvoicePublisherHelper::generate_invoice_file($invoice->id, $va_number, $bca_va_number);

            $whatsapp_notification_result = WhatsappNotificationHelper::send_invoice_notification_message_template_custom(
                [
                    "user_name" => $student->name == null ? "NULL" : $student->name,// "dewi",
                    "number" => $student->backtrack_student_whatsapp_number,
                    "variabel" => [
                        "{{1}}" => "(text)01 " . $payment_for_month_name . " " . date("Y"),
                        "{{2}}" => "(text)" . $student->name,
                        "{{3}}" => "(text)" . $payment_for_month_name . date("Y"),
                        "{{4}}" => "(text)" . $invoice_path,
                    ]
                ]
            );
            // dd ($whatsapp_notification_result);
        }
    }
}
