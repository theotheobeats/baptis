<?php

namespace App\Helpers;

use App\Models\ApiEspayPaymentNotification;
use App\Models\InvoicePayment;
use App\Models\InvoiceReconciliation;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentGatewayHelper
{
    // public static $MASPION_URL = "http://10.20.93.2:813/rest/merchant/sendinvoice";

    public static $MASPION_URL = "https://api.espay.id/rest/merchant/sendinvoice";
    public static function register_invoice($invoice_reconciliation_id)
    {
        $invoice_reconciliation = InvoiceReconciliation::withTrashed()->find($invoice_reconciliation_id);
        $virtual_account_number = $invoice_reconciliation->va_number;
        $invoice_amount = $invoice_reconciliation->invoice_amount;

        // Publish ke maspion
        $payment_url = self::$MASPION_URL;

        $base_va_section_1 = $invoice_reconciliation->base_va_section_1;
        $base_va_section_2 = $invoice_reconciliation->base_va_section_2;
        $base_va_section_3 = $invoice_reconciliation->base_va_section_3;



        $signature_key = "PTYPB@B1new2024";
        $rq_uuid = rand(1, 1000);
        $rq_datetime = date("Y-m-d H:i:s");
        $order_id = $base_va_section_1 . $base_va_section_2 . $base_va_section_3;
        $amount = 300000;
        $ccy = "IDR";
        $comm_code = "YPBAB01391";
        $mode = "SENDINVOICE";
        $bank_code = 157;

        $signature_string = strtoupper("##$signature_key##$rq_uuid##$rq_datetime##$order_id##$amount##$ccy##$comm_code##$mode##");
        $signature = hash('sha256', $signature_string);

        $student = Student::withTrashed()->find($invoice_reconciliation->student_id);

        // try {
            // $http_client = new \GuzzleHttp\Client();
            $http_response = Http::post($payment_url, [
                "form_params" => [
                    'rq_uuid' => $rq_uuid, 
                    'rs_datetime' => $rq_datetime,
                    'order_id' => $order_id,
                    'amount' => $amount,
                    'ccy' => $ccy,
                    'comm_code' => $comm_code,
                    'remark1' => $student != null ? $student->name : "Tagihan Pembayaran " . $order_id,
                    'remark2' => "",
                    'remark3' => "",
                    'bank_code' => $bank_code,
                    'signature' => $signature,
                ]
            ])->throw(function ($response, $e) {
                $response_status = $response->status();
                $response_json = $response->json();
                dd($response_json);
            });
    
            // $server_response_code = $http_request->getStatusCode();
            $server_response = json_decode($http_response->getBody(), true);
    
            $server_response_json = json_encode($server_response);
            $invoice_reconciliation->other_col_1 = $server_response_json;
            $invoice_reconciliation->save();


    
        //     return $server_response_json;
        // } catch (HttpClientException $e) {
        //     return response()->json($e);
        // }
        

        

        // or when your server returns json
        // $content = json_decode($response->getBody(), true);


        // Publish ke bca
    }


    public static $MASPION_OPEN_INVOICE_URL = "https://api.espay.id/rest/merchant/sendinvoice";
    public static $MASPION_CLOSE_INVOICE_URL = "https://api.espay.id/rest/merchant/closeinvoice";

    public static function maspion_send_invoice($invoice_reconciliation_id)
    {
        $invoice_reconciliation = InvoiceReconciliation::withTrashed()->find($invoice_reconciliation_id);
        $virtual_account_number = $invoice_reconciliation->va_number;
        $invoice_amount = $invoice_reconciliation->invoice_amount;

        // Publish ke maspion

        $base_va_section_1 = $invoice_reconciliation->base_va_section_1;
        $base_va_section_2 = $invoice_reconciliation->base_va_section_2;
        $base_va_section_3 = $invoice_reconciliation->base_va_section_3;


        $signature_key = "PTYPB@B1new2024";
        $rq_uuid = rand(1, 1000);
        $rq_datetime = date("Y-m-d H:i:s");
        // $order_id = $base_va_section_1 . $base_va_section_2 . $base_va_section_3;
        $order_id = $base_va_section_2 . $base_va_section_3;
        $amount = $invoice_reconciliation->invoice_amount;
        $ccy = "IDR";
        $comm_code = "YPBAB01391";
        $mode = "SENDINVOICE";
        $bank_code = 157;

        $signature_string = strtoupper("##$signature_key##$rq_uuid##$rq_datetime##$order_id##$amount##$ccy##$comm_code##$mode##");
        $signature = hash('sha256', $signature_string);
        $student = Student::withTrashed()->find($invoice_reconciliation->student_id);

        $http_response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post(self::$MASPION_OPEN_INVOICE_URL, [
                'rq_uuid' => $rq_uuid, 
                'rq_datetime' => $rq_datetime,
                'order_id' => $order_id,
                'amount' => $amount,
                'ccy' => $ccy,
                'comm_code' => $comm_code,
                'remark1' => $student != null ? $student->name : "Tagihan Pembayaran " . $order_id,
                'remark2' => "",
                'remark3' => "",
                'bank_code' => $bank_code,
                'signature' => $signature,
            ]);

        if ($http_response->successful()) {
            $data = $http_response->json();
            // echo "CONNECT SUCCESS";
            return $data;
            // dd($data);
        } else {
            $error = $http_response->body();
            // echo "CONNECT ERROR";
            // dd($error);
        }
    }

    public static function maspion_close_invoice($_order_id)
    {
        $signature_key = "PTYPB@B1new2024";
        $rq_uuid = rand(1, 1000);
        $rq_datetime = date("Y-m-d H:i:s");
        $order_id = $_order_id;
        $comm_code = "YPBAB01391";
        $mode = "CLOSEDINVOICE";

        $signature_string = strtoupper("##$signature_key##$rq_uuid##$rq_datetime##$order_id##$comm_code##$mode##");
        $signature = hash('sha256', $signature_string);

        $http_response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post(self::$MASPION_CLOSE_INVOICE_URL, [
                'rq_uuid' => $rq_uuid, 
                'rq_datetime' => $rq_datetime,
                'order_id' => $order_id,
                'comm_code' => $comm_code,
                'signature' => $signature,
            ]);

        if ($http_response->successful()) {
            $data = $http_response->json();
            if ($data['error_code'] == "0000") {
                $invoice_reconciliation = InvoiceReconciliation::withTrashed()->where("maspion_va_number", "=", $_order_id)->whereNull("inactive_at")->first();
                if ($invoice_reconciliation != null) {
                    $invoice_reconciliation->inactive_at = now();
                    $invoice_reconciliation->inactive_reason = "Permintaan tutup tagihan";
                    $invoice_reconciliation->inactive_by = 1;
                    $invoice_reconciliation->save();
                }
            }
            // echo "CONNECT SUCCESS";
            // dd($data);
        } else {
            $error = $http_response->body();
            // echo "CONNECT ERROR";
            // dd($error);
        }
    }

    public static function receive_payment_notification(Request $request)
    {
        // Close invoice
        // Cek ada tagihan tersisa?
        // Jika ada maka publish

        try {
            $paymentNotification = new ApiEspayPaymentNotification([
                'success_flag' => "",//$request->success_flag,
                'error_message' => "",//$request->error_message,
                'reconcile_id' => "",//$request->reconcile_id,
                'order_id' => $request->order_id,
                'reconcile_datetime' => "",//$request->reconcile_datetime,
                'ss_json' => json_encode($request->all()),
            ]);

            $payment_amount = $request->amount;
            $order_id = $request->order_id;
            if (!isset($order_id)) {
                // Ada sesuatu yang salah
            }

            if (!isset($payment_amount)) {
                // Ada sesuatu yang salah
            }

            if ($payment_amount < 0) {
                // Ada sesuatu yang salah
            }

            // Kirim notif WA pembayaran diterima
            // WhatsappNotificationHelper::send_message_template_custom([
            //     "user_name" => $student->name,
            //     "number" => $student->backtrack_student_whatsapp_number,
            //     "variabel" => [
            //         "{{1}}" => "(text)" . DataHelper::get_month_name($invoice->payment_for_month - 1) . " " . $invoice->payment_for_year,
            //         "{{2}}" => "(text)" . $student->name,
            //         "{{3}}" => "(text)" . DataHelper::get_month_name($invoice->payment_for_month - 1),
            //         "{{4}}" => "(text)" . $invoice_information,
            //         "{{5}}" => "(text)" . "Rp" . number_format($invoice_total),
            //         "{{6}}" => "(text)" . "Nomor VA",
            //     ]
            // ]);
    
            $success_flag = $error_message = $reconcile_id = $order_id = $reconcile_datetime = "";
            if ($paymentNotification->save()) {
                $success_flag = "1";
                $error_message = "Success";
                $reconcile_id = $paymentNotification->id;
                $order_id = $paymentNotification->order_id;
                $reconcile_datetime = $paymentNotification->created_at;

                
    
                // Lakukan pelunasan
            } else {
                $success_flag = 0;
                $error_message = "Invalid request";
            }
    
            return "$success_flag,$error_message,$reconcile_id,$order_id,$reconcile_datetime";
        } catch (Exception $e) {

        }
        
    }



    public static function maspion_custom_send_invoice($va_number, $amount, $note)
    {
        $signature_key = "PTYPB@B1new2024";
        $rq_uuid = rand(1, 1000);
        $rq_datetime = date("Y-m-d H:i:s");
        // $order_id = $base_va_section_1 . $base_va_section_2 . $base_va_section_3;
        $order_id = $va_number;
        $ccy = "IDR";
        $comm_code = "YPBAB01391";
        $mode = "SENDINVOICE";
        $bank_code = 157;

        $signature_string = strtoupper("##$signature_key##$rq_uuid##$rq_datetime##$order_id##$amount##$ccy##$comm_code##$mode##");
        $signature = hash('sha256', $signature_string);

        $http_response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post(self::$MASPION_OPEN_INVOICE_URL, [
                'rq_uuid' => $rq_uuid, 
                'rq_datetime' => $rq_datetime,
                'order_id' => $order_id,
                'amount' => $amount,
                'ccy' => $ccy,
                'comm_code' => $comm_code,
                'remark1' => "Tagihan " . $note,
                'remark2' => "",
                'remark3' => "",
                'bank_code' => $bank_code,
                'signature' => $signature,
            ]);

        if ($http_response->successful()) {
            $data = $http_response->json();
            // echo "CONNECT SUCCESS";
            return $data;
            // dd($data);
        } else {
            $error = $http_response->body();
            // echo "CONNECT ERROR";
            // dd($error);
        }
    }
}