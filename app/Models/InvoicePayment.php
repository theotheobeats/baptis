<?php

namespace App\Models;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Helpers\HasTrackHistory;
use App\Helpers\WhatsappNotificationHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoicePayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "invoice_payments";

    // Pembayaran dari frontend
    public static function do_manual_invoice_payment($id, Request $request)
    {
        try {
            DB::beginTransaction();
            // dd("asddasd");

            $selected_due = $request->selected_due;
            $selected_invoice_detail_id_list = $request->selected_invoice_detail_id;
            $bank_id = $request->bank_id;
            $pay_amount = $request->pay_amount;
            $date = $request->date;
            $note = $request->note;

            $available_pay_amount = $pay_amount;

            // Cek apakah jumlah bayar == jumlah ditagih (YA?)
            // Input Seperti Biasa
            $invoice = Invoice::find($id);
            $bill_price = $invoice->bill_price;

            $month_payment_amount = count(InvoicePayment::withTrashed()->where("created_at", "LIKE", date("Y-m") . "%")
                ->select("id")->get()) + 1;
            $payment_code = "PAY-" . date("ymd") . "-" . str_pad($month_payment_amount, 4, "0", STR_PAD_LEFT);

            $invoice_payment = new InvoicePayment;
            $invoice_payment->invoice_id = $id;
            $invoice_payment->bank_id = $bank_id;
            $invoice_payment->code = $payment_code;
            $invoice_payment->price = $pay_amount;
            $invoice_payment->date = $date;
            $invoice_payment->note = $note;
            $invoice_payment->student_id = $invoice->student_id;
            $invoice_payment->created_by = UserInfoHelper::employee_id();
            $invoice_payment->save();

            // Cek apakah jumlah bayar < jumlah tertagih
            // Input pembayaran seperti biasa

            foreach ($selected_invoice_detail_id_list as $selected_invoice_detail_id) {
                $invoice_detail = InvoiceDetail::find($selected_invoice_detail_id);
                $invoice_detail_payment = new InvoiceDetailPayment;
                $invoice_detail_payment->invoice_id = $id;
                $invoice_detail_payment->invoice_detail_id = $selected_invoice_detail_id;
                $invoice_detail_payment->invoice_payment_id = $invoice_payment->id;
                $invoice_detail_payment->student_id = $invoice->student_id;
                $invoice_detail_payment->price = $invoice_detail->price;
                $invoice_detail_payment->created_by = UserInfoHelper::employee_id();
                $invoice_detail_payment->save();

                $invoice_detail->payed_amount = $invoice_detail->price;
                $invoice_detail->status = "paid";
                $invoice_detail->save();

                // Input cashflow
                $due = Due::withTrashed()->find($invoice_detail->due_id);
                FinanceHelper::add_cashflow($due->finance_account_id, 0, $invoice_detail->price, $payment_code, $payment_code, "Pembayaran iuran " . $due->name . " - " . $note, UserInfoHelper::employee_id(), $date);
            }

            // Kurangi nilai tagihan
            $invoice->payed_amount = $invoice->payed_amount + $pay_amount;
            $invoice->bill_price = $invoice->bill_price - $pay_amount;
            $invoice->save();


            // Send WA
            // Generate PDF bukti bayar
            $payment_proof_path = InvoicePayment::generate_payment_proof($invoice_payment->id);

            $invoice = Invoice::withTrashed()->find($invoice_payment->invoice_id);
            $student = Student::withTrashed()->find($invoice->student_id);

// Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            $invoice_payment_id =  Crypt::encrypt($invoice_payment->id);

            if ($request->send_wa){
                // Kirim WA bukti bayar
                WhatsappNotificationHelper::send_payment_proof_template_custom(
                    [
                        "user_name" => "dewi",
                        "number" => $student->backtrack_student_whatsapp_number,
                        "content_header" => url($payment_proof_path),
                        "variabel" => [
                            "{{1}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('DD MMMM YYYY'),
                            "{{2}}" => "(text)" . $student->name,
                            "{{3}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('MMMM YYYY'),
                            "{{4}}" => "(text)" . $payment_proof_path,
                        ]
                    ]
                );

                return ResponseHelper::response_success_with_data("Proses Input Berhasil", "Data telah disimpan dan invoice diproses pengiriman WA", [
                    "invoice_payment_id" => $invoice_payment_id
                ]);
            }

            $wa_message = "https://wa.me/6282281209606?text=*Tagihan Akademik*%0A%0ATanggal: *[TANGGAL]*%0AKepada: Orang Tua/Wali Murid *[NAMA MURID]*%0A%0AKami harap Anda dalam keadaan sehat dan sejahtera. Terima kasih atas kepercayaan Bapak/Ibu kepada Sekolah Baptis Palembang. Berikut adalah rincian biaya akademik *[NAMA MURID]* untuk bulan *[BULAN TAHUN]*:%0A%0A1. *[NAMA KURSUS]*%0A%E2%80%A2 Biaya : Rp. [BIAYA KURSUS]%0A2. *[NAMA KURSUS]*%0A%E2%80%A2 Biaya : Rp.[BIAYA KURSUS]%0A3. *[NAMA KURSUS]*%0A%E2%80%A2 Biaya : Rp. [BIAYA KURSUS]%0ATotal Pembayaran: Rp. *[TOTAL BIAYA]*%0A%0ACara Pembayaran: %0ASilakan lakukan pembayaran melalui virtual account dengan nomor: *[Nomor Virtual Account]*%0A%0AMohon untuk melakukan pembayaran sesuai dengan jumlah yang tertera di atas sebelum tanggal jatuh tempo. Terima kasih atas partisipasi Anda dalam kursus-kursus kami. Jika ada pertanyaan, jangan ragu untuk menghubungi kami.%0A%0AHormat kami,%0ASekolah Baptis Palembang";
            return ResponseHelper::response_success_with_data("Proses Input Berhasil", "Data telah disimpan", [
                "wa_message" => $wa_message,
                "invoice_payment_id" => $invoice_payment_id
            ]);
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }


    // Pembayaran dari bank
    public static function do_store($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $selected_due = $request->selected_due;
            $bank_id = $request->bank_id;
            $pay_amount = $request->pay_amount;
            $note = $request->note;

            $available_pay_amount = $pay_amount;

            // Cek apakah jumlah bayar == jumlah ditagih (YA?)
            // Input Seperti Biasa
            $invoice = Invoice::find($id);
            $bill_price = $invoice->bill_price;

            $month_payment_amount = count(InvoicePayment::withTrashed()->where("created_at", "LIKE", date("Y-m") . "%")
                ->select("id")->get()) + 1;
            $payment_code = "PAY-" . date("ymd") . "-" . str_pad($month_payment_amount, 4, "0", STR_PAD_LEFT);

            $invoice_payment = new InvoicePayment;
            $invoice_payment->invoice_id = $id;
            $invoice_payment->bank_id = $bank_id;
            $invoice_payment->code = $payment_code;
            $invoice_payment->price = $pay_amount;
            $invoice_payment->note = $note;
            $invoice_payment->student_id = $invoice->student_id;
            $invoice_payment->created_by = UserInfoHelper::employee_id();
            $invoice_payment->save();

            // Cek apakah jumlah bayar < jumlah tertagih
            // Input pembayaran seperti biasa

            $invoice_details = InvoiceDetail::where("invoice_id", "=", $id)
                ->whereIn("due_id", $selected_due)
                ->whereRaw("payed_amount < price")
                ->orderBy("id", "asc")
                ->get();

            foreach ($invoice_details as $invoice_detail) {

                // Cek kalau sisa saldo saat ini tidak cukup untuk bayar maka bayar sebagian (sisa saldo > 0)
                if ($available_pay_amount > 0) {
                    $invoice_detail_bill = $invoice_detail->price - $invoice_detail->payed_amount;

                    $invoice_detail_payment = new InvoiceDetailPayment;
                    $invoice_detail_payment->invoice_id = $id;
                    $invoice_detail_payment->invoice_detail_id = $invoice_detail->id;
                    $invoice_detail_payment->invoice_payment_id = $invoice_payment->id;
                    $invoice_detail_payment->student_id = $invoice->student_id;

                    // Kalau sisa saldo saat ini cukup untuk bayar maka lunasi
                    if ($invoice_detail_bill <= $available_pay_amount) {

                        // Input Pembayaran
                        $invoice_detail_payment->price = $invoice_detail->price - $invoice_detail->payed_amount;

                        // Lunasi
                        $invoice_detail->payed_amount = $invoice_detail_bill + $invoice_detail->payed_amount;
                        $invoice_detail->status = "paid";
                        $invoice_detail->save();

                        // Kurangi saldo baru simpan nilai bayar
                        $available_pay_amount = $available_pay_amount - $invoice_detail_payment->price;


                        // Input cashflow
                        $due = Due::withTrashed()->find($invoice_detail->due_id);
                        FinanceHelper::add_cashflow($due->finance_account_id, 0, $pay_amount, $payment_code, $payment_code, "Pembayaran tagihan siswa " . $invoice->code . " - " . $note, UserInfoHelper::employee_id(), date("Y-m-d"));

                    } else {
                        // Bayar sebagian
                        $invoice_detail->payed_amount = $invoice_detail->payed_amount + $available_pay_amount;
                        $invoice_detail->save();

                        // Input Pembayaran
                        $invoice_detail_payment->price = $available_pay_amount;

                        // Setelah simpan baru update saldo
                        $available_pay_amount = 0;

                        // Input cashflow
                        $due = Due::withTrashed()->find($invoice_detail->due_id);
                        FinanceHelper::add_cashflow($due->finance_account_id, 0, $pay_amount, $payment_code, $payment_code, "Pembayaran iuran " . $due->name . " - " . $note, UserInfoHelper::employee_id(), date("Y-m-d"));
                    }

                    $invoice_detail_payment->created_by = UserInfoHelper::employee_id();
                    $invoice_detail_payment->save();
                } else {
                    break;
                }
            }

            // Cek jika ada lebih bayar
            if ($available_pay_amount > 0) {
                // Lakukan handling saat terjadi lebih bayar

                // Jumlah terutang dibuat jadi 0, kalau tidak nanti tagihan selanjutnya lebih sedikit
                $invoice->payed_amount = $invoice->price;
                $invoice->bill_price = 0;
                $invoice->save();
            } else {

                // Kurangi nilai tagihan
                $invoice->payed_amount = $invoice->payed_amount + $pay_amount;
                $invoice->bill_price = $invoice->bill_price - $pay_amount;
                $invoice->save();
            }



            // Send WA
            // Generate PDF bukti bayar
            $payment_proof_path = InvoicePayment::generate_payment_proof($invoice_payment->id);

            $invoice = Invoice::withTrashed()->find($invoice_payment->invoice_id);
            $student = Student::withTrashed()->find($invoice->student_id);

            // Kirim WA bukti bayar
            // WhatsappNotificationHelper::send_payment_proof_template_custom(
            //     [
            //         "user_name" => $student->name,
            //         "number" => $student->backtrack_student_whatsapp_number,
            //         "content_header" => url($payment_proof_path),
            //         "variabel" => [
            //             "{{1}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('DD MMMM YYYY'),
            //             "{{2}}" => "(text)" . $student->name,
            //             "{{3}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('MMMM YYYY'),
            //             "{{4}}" => "(text)" . $payment_proof_path,
            //         ]
            //     ]
            // );

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            $wa_message = "https://wa.me/6282281209606?text=*Tagihan Akademik*%0A%0ATanggal: *[TANGGAL]*%0AKepada: Orang Tua/Wali Murid *[NAMA MURID]*%0A%0AKami harap Anda dalam keadaan sehat dan sejahtera. Terima kasih atas kepercayaan Bapak/Ibu kepada Sekolah Baptis Palembang. Berikut adalah rincian biaya akademik *[NAMA MURID]* untuk bulan *[BULAN TAHUN]*:%0A%0A1. *[NAMA KURSUS]*%0A%E2%80%A2 Biaya : Rp. [BIAYA KURSUS]%0A2. *[NAMA KURSUS]*%0A%E2%80%A2 Biaya : Rp.[BIAYA KURSUS]%0A3. *[NAMA KURSUS]*%0A%E2%80%A2 Biaya : Rp. [BIAYA KURSUS]%0ATotal Pembayaran: Rp. *[TOTAL BIAYA]*%0A%0ACara Pembayaran: %0ASilakan lakukan pembayaran melalui virtual account dengan nomor: *[Nomor Virtual Account]*%0A%0AMohon untuk melakukan pembayaran sesuai dengan jumlah yang tertera di atas sebelum tanggal jatuh tempo. Terima kasih atas partisipasi Anda dalam kursus-kursus kami. Jika ada pertanyaan, jangan ragu untuk menghubungi kami.%0A%0AHormat kami,%0ASekolah Baptis Palembang";
            $invoice_payment_id =  Crypt::encrypt($invoice_payment->id);
            return ResponseHelper::response_success_with_data("Proses Input Berhasil", "Data telah disimpan", [
                "wa_message" => $wa_message,
                "invoice_payment_id" => $invoice_payment_id
            ]);
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        }
    }




    // Digunakan saat pembayaran dari VA
    public static function do_payment($invoice_id, $payment_amount, $bank_id, $rq_uuid)
    {
        // try {
        //     DB::beginTransaction();

            $id = $invoice_id;
            // $selected_due = $request->selected_due;
            $bank_id = $bank_id;
            $pay_amount = $payment_amount;
            $note = $rq_uuid;

            $available_pay_amount = $pay_amount;

            // Cek apakah jumlah bayar == jumlah ditagih (YA?)
            // Input Seperti Biasa
            $invoice = Invoice::find($id);
            if ($invoice == null) { return false; }
            $bill_price = $invoice->bill_price;

            $month_payment_amount = count(InvoicePayment::withTrashed()->where("created_at", "LIKE", date("Y-m") . "%")
                ->select("id")->get()) + 1;
            $payment_code = "PAY-" . date("ymd") . "-" . str_pad($month_payment_amount, 4, "0", STR_PAD_LEFT);

            $invoice_payment = new InvoicePayment;
            $invoice_payment->invoice_id = $id;
            $invoice_payment->bank_id = $bank_id;
            $invoice_payment->code = $payment_code;
            $invoice_payment->price = $pay_amount;
            $invoice_payment->note = $note . " [VA]" . $rq_uuid;
            $invoice_payment->student_id = $invoice->student_id;
            $invoice_payment->created_by = 1; // UserInfoHelper::employee_id();
            $invoice_payment->save();

            // Cek apakah jumlah bayar < jumlah tertagih
            // Input pembayaran seperti biasa

            $invoice_details = InvoiceDetail::where("invoice_id", "=", $id)
                // ->whereIn("due_id", $selected_due)
                ->whereRaw("payed_amount < price")
                ->orderBy("id", "asc")
                ->get();

            foreach ($invoice_details as $invoice_detail) {

                // Cek kalau sisa saldo saat ini tidak cukup untuk bayar maka bayar sebagian (sisa saldo > 0)
                if ($available_pay_amount > 0) {
                    $invoice_detail_bill = $invoice_detail->price - $invoice_detail->payed_amount;

                    $invoice_detail_payment = new InvoiceDetailPayment;
                    $invoice_detail_payment->invoice_id = $id;
                    $invoice_detail_payment->invoice_detail_id = $invoice_detail->id;
                    $invoice_detail_payment->invoice_payment_id = $invoice_payment->id;
                    $invoice_detail_payment->student_id = $invoice->student_id;

                    // Kalau sisa saldo saat ini cukup untuk bayar maka lunasi
                    if ($invoice_detail_bill <= $available_pay_amount) {

                        // Input Pembayaran
                        $invoice_detail_payment->price = $invoice_detail->price - $invoice_detail->payed_amount;

                        // Lunasi
                        $invoice_detail->payed_amount = $invoice_detail_bill + $invoice_detail->payed_amount;
                        $invoice_detail->status = "paid";
                        $invoice_detail->save();

                        // Kurangi saldo baru simpan nilai bayar
                        $available_pay_amount = $available_pay_amount - $invoice_detail_payment->price;


                        // Input cashflow
                        $due = Due::withTrashed()->find($invoice_detail->due_id);
                        FinanceHelper::add_cashflow($due->finance_account_id, 0, $pay_amount, $payment_code, $payment_code, "Pembayaran tagihan siswa melalui VA " . $invoice->code . " - " . $note, 1, date("Y-m-d"));//UserInfoHelper::employee_id());

                    } else {
                        // Bayar sebagian
                        $invoice_detail->payed_amount = $invoice_detail->payed_amount + $available_pay_amount;
                        $invoice_detail->save();

                        // Input Pembayaran
                        $invoice_detail_payment->price = $available_pay_amount;

                        // Setelah simpan baru update saldo
                        $available_pay_amount = 0;

                        // Input cashflow
                        $due = Due::withTrashed()->find($invoice_detail->due_id);
                        FinanceHelper::add_cashflow($due->finance_account_id, 0, $pay_amount, $payment_code, $payment_code, "Pembayaran iuran melalui VA " . $due->name . " - " . $note, 1, date("Y-m-d"));// UserInfoHelper::employee_id());
                    }

                    $invoice_detail_payment->created_by = 1; // UserInfoHelper::employee_id();
                    $invoice_detail_payment->save();
                } else {
                    break;
                }
            }

            // Cek jika ada lebih bayar
            if ($available_pay_amount > 0) {
                // Lakukan handling saat terjadi lebih bayar

                // Jumlah terutang dibuat jadi 0, kalau tidak nanti tagihan selanjutnya lebih sedikit
                $invoice->payed_amount = $invoice->price;
                $invoice->bill_price = 0;
                $invoice->save();
            } else {

                // Kurangi nilai tagihan
                $invoice->payed_amount = $invoice->payed_amount + $pay_amount;
                $invoice->bill_price = $invoice->bill_price - $pay_amount;
                $invoice->save();
            }


            // TODO: Send WA
            // Generate PDF bukti bayar

            // Generate file bukti pembayaran
            $payment_proof_path = InvoicePayment::generate_payment_proof($invoice_payment->id);

            $invoice = Invoice::withTrashed()->find($invoice_payment->invoice_id);
            $student = Student::withTrashed()->find($invoice->student_id);

            // Kirim WA bukti bayar
            WhatsappNotificationHelper::send_payment_proof_template_custom(
                [
                    "user_name" => "dewi",
                    "number" => $student->backtrack_student_whatsapp_number,
                    "content_header" => url($payment_proof_path),
                    "variabel" => [
                        "{{1}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('DD MMMM YYYY'),
                        "{{2}}" => "(text)" . $student->name,
                        "{{3}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('MMMM YYYY'),
                        "{{4}}" => "(text)" . $payment_proof_path,
                    ]
                ]
            );

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            // DB::commit();

            return $invoice_payment;
            // $invoice_payment_id =  Crypt::encrypt($invoice_payment->id);
            // return ResponseHelper::response_success_with_data("Proses Input Berhasil", "Data telah disimpan", [
            //     "invoice_payment_id" => $invoice_payment_id
            // ]);
        // } catch (Exception $e) {
        //     // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
        //     DB::rollBack();
        //     return $e->getMessage();
        //     // return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal!" . $e);
        // }
    }


    public static function generate_payment_proof($payment_id)
    {
        // $decrypt = CryptoHelper::decrypt($id);
        // if (!$decrypt->success) return $decrypt->error_response;
        $invoice_payment = new InvoicePayment;
        $invoice_payment = $invoice_payment->join("invoices", "invoices.id", "=", "invoice_payments.invoice_id");
        $invoice_payment = $invoice_payment->where("invoice_payments.id", "=", $payment_id);
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

        $baseurl = "https://accounting.sekolahbaptispalembang.com/"; // url('/check/payment');
        $invoice_id = $invoice_payment->invoice_id;
        $payment_code = $invoice_payment->code;
        $created_at = str_replace(" ", ";", $invoice_payment->created_at);
        $qrcode = QrCode::format('svg')->size(100)->generate("{$baseurl}/{$invoice_id}/{$payment_code}/{$created_at}");

        $pdf = Pdf::loadView('docs.payment-receipt', [
            "invoice_payment" => $invoice_payment,
            "invoice_detail_payments" => $invoice_detail_payments,
            "student" => $student,
            "bank" => $bank,
            "qrcode" => base64_encode($qrcode),
            "is_pdf" => "true"
        ]);
        $_relative_file_path = "payment/" . $invoice_payment->code . "-" . time() . ".pdf";
        $file_path = public_path($_relative_file_path);
        $student_password = "19900101";
        if ($student != null && $student->birth_date != null && $student->birth_date != "") {
            $student_password = date('Ymd', strtotime($student->birth_date));
        }
        $pdf->setEncryption("PASS@SISXBAPTIS2024", $student_password, ['copy', 'print']);
        $file_output = $pdf->output();
        // Storage::put($file_path, $pdf->output());
        file_put_contents($file_path, $file_output);
        return "https://accounting.sekolahbaptispalembang.com/" .  $file_path; // $pdf->download('invoice.pdf');
    }
}
