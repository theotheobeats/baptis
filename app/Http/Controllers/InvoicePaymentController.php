<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Models\Classroom;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InvoiceDetailPayment;
use App\Models\InvoiceReconciliation;
use Psy\Readline\Hoa\FileLink;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoicePaymentController extends Controller
{
    public function do_invoice_payment(Request $request)
    {
        $decrypt = CryptoHelper::decrypt($request->invoice_id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = InvoicePayment::do_store($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function do_manual_invoice_payment(Request $request)
    {
        $decrypt = CryptoHelper::decrypt($request->invoice_id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = InvoicePayment::do_manual_invoice_payment($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function get_payment_receipt($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $invoice_payment = new InvoicePayment;
        $invoice_payment = $invoice_payment->join("invoices", "invoices.id", "=", "invoice_payments.invoice_id");
        $invoice_payment = $invoice_payment->where("invoice_payments.id", "=", $decrypt->id);
        $invoice_payment = $invoice_payment->select(
            "invoice_payments.*",
            "invoices.student_id",
        );
        $invoice_payment = $invoice_payment->first();

        if($invoice_payment == null){ return abort(404); }
        $invoice_detail_payments = new InvoiceDetailPayment;
        $invoice_detail_payments = $invoice_detail_payments->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id");
        $invoice_detail_payments = $invoice_detail_payments->join("dues", "dues.id", "=", "invoice_details.due_id");
        $invoice_detail_payments = $invoice_detail_payments->where("invoice_detail_payments.invoice_payment_id", "=", $invoice_payment->id);
        $invoice_detail_payments = $invoice_detail_payments->select(
            "invoice_detail_payments.*",
            "invoice_detail_payments.price as price",
            "invoice_details.payment_for_month as invoice_detail_payment_for_month",
            "invoice_details.payment_for_year as invoice_detail_payment_for_year",
            "dues.name as due_name",
        );
        $invoice_detail_payments = $invoice_detail_payments->get();

        $student = Student::find($invoice_payment->student_id);
        $bank = Bank::find($invoice_payment->bank_id);

        $baseurl = url('/check/payment');
        $invoice_id = $invoice_payment->invoice_id;
        $payment_code = $invoice_payment->code;
        $created_at = str_replace(" ", ";", $invoice_payment->created_at);
        $qrcode = QrCode::format('svg')->size(100)->generate("{$baseurl}/{$invoice_id}/{$payment_code}/{$created_at}");

        // return view('docs.payment-receipt', [
        //     "invoice_payment" => $invoice_payment,
        //     "invoice_detail_payments" => $invoice_detail_payments,
        //     "student" => $student,
        //     "bank" => $bank,
        //     "qrcode" => $qrcode
        // ]);

        $pdf = Pdf::loadView('docs.payment-receipt', [
            "invoice_payment" => $invoice_payment,
            "invoice_detail_payments" => $invoice_detail_payments,
            "student" => $student,
            "bank" => $bank,
            "qrcode" => base64_encode($qrcode),
            "is_pdf" => "true"
        ]);
        return $pdf->download('invoice.pdf');
    }

    // Dari QR Code
    public function check_payment($invoice_id, $payment_code, $created_at)
    {
        $invoice_payment = new InvoicePayment;
        $invoice_payment = $invoice_payment->where("invoice_id", "=", $invoice_id);
        $invoice_payment = $invoice_payment->where("code", "=", $payment_code);
        $invoice_payment = $invoice_payment->where("created_at", "=", str_replace(";", " ", $created_at));
        $invoice_payment = $invoice_payment->first();

        if($invoice_payment == null){
            return abort(404);
        }

        $invoice_detail_payments = new InvoiceDetailPayment;
        $invoice_detail_payments = $invoice_detail_payments->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id");
        $invoice_detail_payments = $invoice_detail_payments->join("dues", "dues.id", "=", "invoice_details.due_id");
        $invoice_detail_payments = $invoice_detail_payments->where("invoice_detail_payments.invoice_payment_id", "=", $invoice_payment->id);
        $invoice_detail_payments = $invoice_detail_payments->select(
            "invoice_detail_payments.*",
            "invoice_detail_payments.price as price",
            "invoice_details.payment_for_month as invoice_detail_payment_for_month",
            "invoice_details.payment_for_year as invoice_detail_payment_for_year",
            "dues.name as due_name",
        );
        $invoice_detail_payments = $invoice_detail_payments->get();

        $student = Student::find($invoice_payment->student_id);
        $bank = Bank::find($invoice_payment->bank_id);

        $baseurl = url('/check/payment');
        $qrcode = QrCode::size(100)->generate("{$baseurl}/{$invoice_id}/{$payment_code}/{$created_at}");

        return view('docs.payment-receipt', [
            "invoice_payment" => $invoice_payment,
            "invoice_detail_payments" => $invoice_detail_payments,
            "student" => $student,
            "bank" => $bank,
            "qrcode" => $qrcode
        ]);
    }

    public function resync_password(Request $request)
    {
        set_time_limit(600);
        $directory = public_path() . "/invoice";
        $files = scandir($directory);
        $fileList = [];
        $filePathList = [];
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $filePath = $directory . '/' . $file;
            
            if (is_file($filePath)) {
                $fileList[] = $file;
                $filePathList[] = $filePath;
                echo $filePath . "<br>";
            }
        }

        $generated_count = 0;
        for ($i = 0; $i < count($fileList); $i++) {
            // 1 NIS
            // 2 Tanggal terbit
            // 3 ID siswa
            // 4 Timestamp

            // echo $fileList[$i];
            // echo $filePathList[$i];
            // echo "<br>";

            $file_data = explode("-", $fileList[$i]);

            if (count($file_data) == 4) {
                $nis = $file_data[0];
                $publish_date = $file_data[1];
                $student_id = $file_data[2];
                $timestamp = $file_data[3];

                echo ($student_id);
                echo "<br>";
                self::regenerate_file($nis, $publish_date, $student_id, $timestamp);
                $generated_count++;
            }

            echo "Total Data : " . count($fileList) . "<br>";
            echo "Total Generate : " . $generated_count;
        }

        // echo json_encode($fileList);
    }


    public function regenerate_file($nis, $publish_date, $student_id, $timestamp)
    {
        $invoice = Invoice::where("student_id", "=", $student_id)->first();
        $invoice_id = $invoice->id;
        $invoice_reconciliation = InvoiceReconciliation::withTrashed()->where("invoice_id", "=", $invoice->id)->orderBy("id", "desc")->whereNull("inactive_at")->first();

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

        // $student_school_group = Classroom::find($student->backtrack_current_classroom_id)->school_group;
        // if ($student_school_group == "TK") {
        //     $doc = 'docs.invoice-tk';
        // } else if ($student_school_group == "SD") {
        //     $doc = 'docs.invoice-sd';
        // } else if ($student_school_group == "SMP") {
        //     $doc = 'docs.invoice-smp';
        // } else {
            $doc = 'docs.invoice';
        // }
        $va_number = $invoice_reconciliation->maspion_va_number;
        $bca_va_number = "06489" . $student->backtrack_class_grade . $student->nis;

        $pdf = Pdf::loadView($doc, [
            "invoice" => $invoice,
            "invoice_details" => $invoice_details,
            "student" => $student,
            "va_number" => $va_number,
            "bca_va_number" => $bca_va_number,
            "is_pdf" => "true"
        ]);
        // $_relative_filepath = "invoice/" . $student->id . "-" . $student->nis . "-" . time() . ".pdf";
        $_relative_filepath = "new-invoice/" . $nis . "-" . $publish_date . "-" . $student_id . "-" . $timestamp;
        $file_path = public_path($_relative_filepath);
        $student_password = "19900101";
        if ($student != null && $student->birth_date != null && $student->birth_date != "") {
            $student_password = date('Ymd', strtotime($student->birth_date));
        }
        $pdf->setEncryption("PASS@SISXBAPTIS2024", $student_password, ['copy', 'print']);
        $file_output = $pdf->output();
        // Storage::put($file_path, $pdf->output());
        file_put_contents($file_path, $file_output);
        return "https://accounting.sekolahbaptispalembang.com/" . $_relative_filepath;
    }
}
